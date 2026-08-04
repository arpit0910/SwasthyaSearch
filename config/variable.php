<?php

return [
    'gemini_key' => env('GEMINI_API_KEY'),
    'gemini_model' => env('GEMINI_MODEL', 'gemini-2.5-flash'),
    'groq_key' => env('GROQ_KEY', env('GROK_KEY')),
    'grok_key' => env('GROK_KEY'),
    'chatbot_disable_ssl_verify' => env('CHATBOT_DISABLE_SSL_VERIFY', false),
    'chatbot_ca_bundle_path' => env('CHATBOT_CA_BUNDLE_PATH', storage_path('app/cacert.pem')),
];
