<?php

return [
    'minute_packages' => [
        ['id' => 'm30',  'minutes' => 30,  'price_eur' => 19.00],
        ['id' => 'm120', 'minutes' => 120, 'price_eur' => 69.00],
        ['id' => 'm300', 'minutes' => 300, 'price_eur' => 159.00],
        ['id' => 'm1000','minutes' => 1000,'price_eur' => 449.00],
    ],

    // Wallet alerts
    'low_balance_warning_minutes' => (int) env('LOW_BALANCE_WARNING_MINUTES', 5),

    // Referral / invites
    'referral_reward_minutes' => (int) env('REFERRAL_REWARD_MINUTES', 5),
];
