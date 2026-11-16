<?php

return [
    /*
    |--------------------------------------------------------------------------
    | OCR (Optical Character Recognition) Service
    |--------------------------------------------------------------------------
    |
    | Supported drivers: "tesseract", "vision", "fallback"
    |
    */
    'ocr' => [
        'default' => env('OCR_DRIVER', 'tesseract'),

        'drivers' => [
            'tesseract' => [
                'binary_path' => env('TESSERACT_PATH', '/usr/bin/tesseract'),
                'languages' => ['eng', 'amh'],
                'psm' => 3, // Page segmentation mode
                'timeout' => 60,
            ],

            'vision' => [
                'api_key' => env('GOOGLE_VISION_API_KEY'),
                'credentials_file' => env('GOOGLE_APPLICATION_CREDENTIALS'),
                'endpoint' => 'https://vision.googleapis.com/v1/images:annotate',
            ],

            'fallback' => [
                'enabled' => true,
            ],
        ],

        'queue' => [
            'queue_name' => env('OCR_QUEUE', 'default'),
            'tries' => 3,
            'timeout' => 120,
            'backoff' => 10,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Domain-Aware Kinship Translation Engine
    |--------------------------------------------------------------------------
    |
    | Supported drivers: "lexicon", "libretranslate", "google"
    |
    */
    'translation' => [
        'driver' => env('TRANSLATION_DRIVER', 'lexicon'),

        'cache' => [
            'enabled' => env('TRANSLATION_CACHE_ENABLED', true),
            'ttl_days' => env('TRANSLATION_CACHE_TTL_DAYS', 7),
            'prefix' => 'genealogy_trans:',
        ],

        'libretranslate' => [
            'url' => env('LIBRETRANSLATE_URL', 'https://libretranslate.com'),
            'api_key' => env('LIBRETRANSLATE_API_KEY'),
            'timeout' => 15,
        ],

        'google' => [
            'api_key' => env('GOOGLE_TRANSLATE_API_KEY'),
            'endpoint' => 'https://translation.googleapis.com/language/translate/v2',
            'timeout' => 15,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Hallucination-Proof Conversational Agent
    |--------------------------------------------------------------------------
    |
    | Supported providers: "deterministic", "openai", "gemini"
    |
    */
    'agent' => [
        'provider' => env('LLM_PROVIDER', 'deterministic'),
        'api_key' => env('LLM_API_KEY'),
        'model' => env('LLM_MODEL', 'gemini-1.5-flash'),
        'temperature' => 0.1, // Strict factual adherence
        'max_tokens' => 1024,
    ],
];
