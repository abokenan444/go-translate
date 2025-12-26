<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Assignment Offer Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for the parallel offer assignment system
    |
    */
    
    // Time limit for partners to accept offers (in minutes)
    'accept_deadline_minutes' => (int) env('ASSIGNMENT_ACCEPT_DEADLINE', 60),
    
    // Maximum number of re-assignment attempts before moving to waiting list
    'max_attempts' => (int) env('ASSIGNMENT_MAX_ATTEMPTS', 7),
    
    // Number of parallel offers to send simultaneously
    'parallel_offers' => (int) env('ASSIGNMENT_PARALLEL_OFFERS', 2),
    
    /*
    |--------------------------------------------------------------------------
    | Partner Eligibility Criteria
    |--------------------------------------------------------------------------
    */
    
    // Minimum quality score required for assignment (1-5 scale)
    'min_quality_score' => (float) env('MIN_PARTNER_QUALITY_SCORE', 3.5),
    
    // Minimum SLA score required for assignment (1-5 scale)
    'min_sla_score' => (float) env('MIN_PARTNER_SLA_SCORE', 3.5),
    
    // Maximum concurrent active assignments per partner
    'max_concurrent_assignments' => (int) env('MAX_CONCURRENT_ASSIGNMENTS', 5),
    
    /*
    |--------------------------------------------------------------------------
    | Scoring Weights
    |--------------------------------------------------------------------------
    |
    | Weights used to calculate overall partner suitability score
    |
    */
    
    'scoring_weights' => [
        'quality_score' => 0.4,      // 40% weight
        'sla_score' => 0.3,          // 30% weight
        'accept_speed' => 0.2,       // 20% weight (faster = better)
        'completion_rate' => 0.1,    // 10% weight
    ],
];
