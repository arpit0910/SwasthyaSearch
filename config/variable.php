<?php

return [
    'gemini_key' => env('GEMINI_API_KEY'),
    'gemini_model' => env('GEMINI_MODEL', 'gemini-3.5-flash'),
    'groq_key' => env('GROQ_KEY', env('GROK_KEY')),
    'grok_key' => env('GROK_KEY'),
];
