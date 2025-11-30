<?php

return [
    'default_culture_key'   => 'sa_marketing',
    'default_tone_key'      => 'friendly',
    'default_industry_key'  => 'generic',
    'default_task_key'      => 'translation.general',

    'fallback_prompt' => <<<PROMPT
You are a professional cultural translator. 
Translate the following text from {source_lang} to {target_lang}, preserving the emotional intent and adapting it to the target culture. 
Avoid literal translation and prefer natural, human-like phrasing.
PROMPT,

    'max_source_length' => 8000,
];
