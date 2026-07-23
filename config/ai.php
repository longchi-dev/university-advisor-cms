<?php
return [
    'ollama' => [
        'base_url' => env('OLLAMA_BASE_URL', 'http://localhost:11434'),
        'model' => env('OLLAMA_MODEL', 'llama3'),
        'model_embedding' => env('OLLAMA_MODEL_EMBEDDING', 'nomic-embed-text'),
    ],

    'ai_job' => [
        'limit' => env('AI_JOB_LIMIT', 500),
    ],

    'retrieval_docs' => [
        'topK' => env('AI_RETRIEVE_DOCS_TOP_K', 5),
        'vector_threshold' => env('AI_RETRIEVE_DOCS_THRESHOLD', 0.73),
    ],

    'guest_user_id' => env('AI_GUEST_USER_ID', '00000000-0000-0000-0000-000000000000'),
];
