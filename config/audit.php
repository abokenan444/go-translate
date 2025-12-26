<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Audit Trail Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for the audit trail and compliance system.
    | Layer 6: Audit & Compliance
    |
    */

    'enabled' => env('AUDIT_TRAIL_ENABLED', true),

    /*
    |--------------------------------------------------------------------------
    | Blockchain Anchoring
    |--------------------------------------------------------------------------
    |
    | External blockchain anchoring for immutable proof of audit records.
    | This provides cryptographic proof that audit logs have not been tampered
    | with by anchoring hashes to public blockchain networks.
    |
    */

    'blockchain_anchoring' => [
        'enabled' => env('BLOCKCHAIN_ANCHORING_ENABLED', false),
        
        // Automatic anchoring schedule (in hours, 0 to disable)
        'auto_anchor_interval' => env('BLOCKCHAIN_ANCHOR_INTERVAL', 24),
        
        // Ethereum configuration
        'ethereum' => [
            'enabled' => env('ETHEREUM_ANCHORING_ENABLED', false),
            'api_key' => env('ETHEREUM_API_KEY'),
            'contract_address' => env('ETHEREUM_CONTRACT_ADDRESS'),
            'network' => env('ETHEREUM_NETWORK', 'mainnet'), // mainnet, goerli, sepolia
            'rpc_url' => env('ETHEREUM_RPC_URL', 'https://mainnet.infura.io/v3/'),
        ],
        
        // Polygon configuration
        'polygon' => [
            'enabled' => env('POLYGON_ANCHORING_ENABLED', false),
            'api_key' => env('POLYGON_API_KEY'),
            'contract_address' => env('POLYGON_CONTRACT_ADDRESS'),
            'network' => env('POLYGON_NETWORK', 'mainnet'), // mainnet, mumbai
            'rpc_url' => env('POLYGON_RPC_URL', 'https://polygon-rpc.com'),
        ],
        
        // Bitcoin configuration (OP_RETURN)
        'bitcoin' => [
            'enabled' => env('BITCOIN_ANCHORING_ENABLED', false),
            'api_key' => env('BITCOIN_API_KEY'),
            'network' => env('BITCOIN_NETWORK', 'mainnet'), // mainnet, testnet
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Audit Log Retention
    |--------------------------------------------------------------------------
    |
    | How long to keep audit logs before archiving.
    | Government compliance typically requires 7-10 years.
    |
    */

    'retention' => [
        'days' => env('AUDIT_RETENTION_DAYS', 3650), // 10 years default
        'archive_after_days' => env('AUDIT_ARCHIVE_AFTER_DAYS', 365), // 1 year
    ],

    /*
    |--------------------------------------------------------------------------
    | Sensitive Events
    |--------------------------------------------------------------------------
    |
    | Events that require immediate blockchain anchoring or special handling.
    |
    */

    'sensitive_events' => [
        'certification_issued',
        'certification_revoked',
        'partner_approved',
        'partner_suspended',
        'government_access',
        'document_verified',
    ],

    /*
    |--------------------------------------------------------------------------
    | Integrity Verification
    |--------------------------------------------------------------------------
    |
    | Automatic integrity verification schedule.
    |
    */

    'integrity_check' => [
        'enabled' => env('AUDIT_INTEGRITY_CHECK_ENABLED', true),
        'schedule' => env('AUDIT_INTEGRITY_CHECK_SCHEDULE', 'daily'), // daily, weekly, monthly
    ],

];
