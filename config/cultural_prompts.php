<?php

return [

    'default_locale' => 'en',

    'fallback_locale' => 'en',

    'forbidden_terms' => [
        'hate speech',
        'sexually explicit',
        'illegal activity',
    ],

    'max_prompt_tokens' => 3000,

    'safety' => [
        'enabled' => true,
        'strict'  => true,
    ],
];
