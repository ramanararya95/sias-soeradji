<?php

return [
    'exports' => [
        'chunk_size' => 1000,
        'temp_path' => sys_get_temp_dir(),
        'csv' => [
            'delimiter' => ',',
            'enclosure' => '"',
            'line_ending' => PHP_EOL,
            'use_bom' => false,
        ],
    ],
    
    'imports' => [
        'read_only' => true,
        'ignore_empty' => false,
        'heading_row' => [
            'formatter' => 'slug',
        ],
    ],
    
    'cache' => [
        'enabled' => true,
        'driver' => 'memory',
        'batch_size' => 1000,
    ],
    
    'queue' => [
        'connection' => null,
        'batch_size' => 1000,
    ],
];