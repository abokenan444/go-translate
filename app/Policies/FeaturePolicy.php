<?php

namespace App\Policies;

use App\Models\Feature;
use App\Models\User;

class FeaturePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->role === 'super_admin';
    }

    public function view(User $user, Feature $feature): bool
    {
        return $user->role === 'super_admin';
    }

    public function create(User $user): bool
    {
        return $user->role === 'super_admin';
    }

    public function update(User $user, Feature $feature): bool
    {
        return $user->role === 'super_admin';
    }

    public function delete(User $user, Feature $feature): bool
    {
        return $user->role === 'super_admin';
    }

    public function restore(User $user, Feature $feature): bool
    {
        return $user->role === 'super_admin';
    }

    public function forceDelete(User $user, Feature $feature): bool
    {
        return $user->role === 'super_admin';
    }
}
