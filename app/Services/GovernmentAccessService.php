<?php

namespace App\Services;

use App\Models\GovEntity;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * Government Access Control Service
 * Enforces security rules for government portal
 */
class GovernmentAccessService
{
    /**
     * Verify government access with all security checks
     */
    public function verifyAccess(Request $request, User $user): array
    {
        $checks = [];

        // 1. Subdomain check
        $checks['subdomain'] = $this->checkSubdomain($request);

        // 2. Account type check
        $checks['account_type'] = $this->checkAccountType($user);

        // 3. Verification status
        $checks['verification'] = $this->checkVerification($user);

        // 4. Entity status
        $checks['entity_status'] = $this->checkEntityStatus($user);

        // 5. IP whitelist (if configured)
        $checks['ip_whitelist'] = $this->checkIpWhitelist($request, $user);

        // 6. Domain allowlist (if configured)
        $checks['domain_allowlist'] = $this->checkDomainAllowlist($user);

        $allPassed = collect($checks)->every(fn($check) => $check['passed'] === true);

        if (!$allPassed) {
            Log::warning('Government access denied', [
                'user_id' => $user->id,
                'checks' => $checks,
                'ip' => $request->ip()
            ]);
        }

        return [
            'allowed' => $allPassed,
            'checks' => $checks,
            'user_id' => $user->id
        ];
    }

    protected function checkSubdomain(Request $request): array
    {
        $host = $request->getHost();
        $isGovSubdomain = str_starts_with($host, 'gov.') 
            || str_starts_with($host, 'government.')
            || str_starts_with($host, 'authority.');

        // Allow localhost in development
        if (app()->environment('local') && str_contains($host, 'localhost')) {
            $isGovSubdomain = true;
        }

        return [
            'name' => 'subdomain_check',
            'passed' => $isGovSubdomain,
            'message' => $isGovSubdomain 
                ? 'Valid government subdomain' 
                : 'Must access from government/authority subdomain'
        ];
    }

    protected function checkAccountType(User $user): array
    {
        $validTypes = ['government'];
        $isValid = in_array($user->account_type, $validTypes);

        return [
            'name' => 'account_type_check',
            'passed' => $isValid,
            'message' => $isValid 
                ? 'Valid government account type' 
                : 'Government account type required'
        ];
    }

    protected function checkVerification(User $user): array
    {
        $isVerified = $user->is_government_verified === true;

        return [
            'name' => 'verification_check',
            'passed' => $isVerified,
            'message' => $isVerified 
                ? 'Account verified' 
                : 'Account pending verification'
        ];
    }

    protected function checkEntityStatus(User $user): array
    {
        $govProfile = $user->governmentProfile;
        
        if (!$govProfile) {
            return [
                'name' => 'entity_status_check',
                'passed' => false,
                'message' => 'No government profile found'
            ];
        }

        $entity = GovEntity::where('official_email', $user->email)
            ->orWhere('entity_code', $govProfile->entity_code ?? '')
            ->first();

        if (!$entity) {
            return [
                'name' => 'entity_status_check',
                'passed' => true, // Allow if no entity configured yet
                'message' => 'No entity restrictions'
            ];
        }

        $isActive = $entity->status === 'active';

        return [
            'name' => 'entity_status_check',
            'passed' => $isActive,
            'message' => $isActive 
                ? 'Entity active' 
                : "Entity status: {$entity->status}"
        ];
    }

    protected function checkIpWhitelist(Request $request, User $user): array
    {
        $govProfile = $user->governmentProfile;
        if (!$govProfile) {
            return ['name' => 'ip_whitelist_check', 'passed' => true, 'message' => 'No IP restrictions'];
        }

        $entity = GovEntity::where('official_email', $user->email)->first();
        if (!$entity || empty($entity->ip_whitelist)) {
            return ['name' => 'ip_whitelist_check', 'passed' => true, 'message' => 'No IP whitelist configured'];
        }

        $clientIp = $request->ip();
        $isAllowed = $entity->isIpAllowed($clientIp);

        return [
            'name' => 'ip_whitelist_check',
            'passed' => $isAllowed,
            'message' => $isAllowed 
                ? 'IP allowed' 
                : 'IP not in whitelist'
        ];
    }

    protected function checkDomainAllowlist(User $user): array
    {
        $govProfile = $user->governmentProfile;
        if (!$govProfile) {
            return ['name' => 'domain_allowlist_check', 'passed' => true, 'message' => 'No domain restrictions'];
        }

        $entity = GovEntity::where('official_email', $user->email)->first();
        if (!$entity || empty($entity->allowed_domains)) {
            return ['name' => 'domain_allowlist_check', 'passed' => true, 'message' => 'No domain allowlist configured'];
        }

        $isAllowed = $entity->isDomainAllowed($user->email);

        return [
            'name' => 'domain_allowlist_check',
            'passed' => $isAllowed,
            'message' => $isAllowed 
                ? 'Domain allowed' 
                : 'Email domain not in allowlist'
        ];
    }

    /**
     * Check if user has specific government role
     */
    public function hasRole(User $user, string $role): bool
    {
        // Check in gov_contacts table
        $contact = \App\Models\GovContact::where('user_id', $user->id)
            ->where('role', $role)
            ->where('status', 'active')
            ->first();

        return $contact !== null;
    }

    /**
     * Check if user can perform action
     */
    public function canPerform(User $user, string $action): bool
    {
        $permissions = [
            'upload_documents' => ['gov_client_operator', 'gov_client_supervisor'],
            'approve_uploads' => ['gov_client_supervisor'],
            'view_compliance' => ['gov_client_supervisor', 'gov_authority_officer', 'gov_authority_supervisor'],
            'audit_certificates' => ['gov_authority_officer', 'gov_authority_supervisor'],
            'freeze_certificate' => ['gov_authority_officer', 'gov_authority_supervisor'],
            'revoke_certificate' => ['gov_authority_supervisor'], // supervisor only
            'request_revocation' => ['gov_authority_officer', 'gov_authority_supervisor'],
            'open_dispute' => ['gov_authority_officer', 'gov_authority_supervisor', 'gov_client_supervisor'],
        ];

        if (!isset($permissions[$action])) {
            return false;
        }

        $allowedRoles = $permissions[$action];
        foreach ($allowedRoles as $role) {
            if ($this->hasRole($user, $role)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get authority scope (entity_id for officers, null for supervisors)
     * Officers see only their entity data, supervisors see all
     */
    public function getAuthorityScope(User $user): ?int
    {
        // Supervisors see all entities (global scope)
        if ($user->account_type === 'gov_authority_supervisor') {
            return null;
        }

        // Officers see only their assigned entity
        if ($user->account_type === 'gov_authority_officer') {
            // Get entity from authority profile
            $authorityProfile = $user->authorityProfile;
            if ($authorityProfile && $authorityProfile->entity_id) {
                return $authorityProfile->entity_id;
            }

            // Fallback: get from government profile if exists
            $govProfile = $user->governmentProfile;
            if ($govProfile && $govProfile->gov_entity_id) {
                return $govProfile->gov_entity_id;
            }
        }

        // Default: no scope (shouldn't reach here if properly configured)
        return null;
    }
}
