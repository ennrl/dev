<?php
return [
    'app' => [
        'name' => 'DevManager',
        'debug' => true,
    ],
    'paths' => [
        'data' => ROOT_PATH . '/dev_data',
        'views' => ROOT_PATH . '/dev_views',
        'lang' => ROOT_PATH . '/dev_lang',
    ],
    'languages' => [
        'ru' => 'Русский',
        'en' => 'English',
        'be' => 'Беларуская'
    ],
    'projects' => [
        'allowed_extensions' => ['html', 'css', 'js', 'php', 'txt'],
        'preview_sizes' => [
            'mobile' => ['width' => 375, 'height' => 667],
            'tablet' => ['width' => 768, 'height' => 1024],
            'desktop' => ['width' => 1280, 'height' => 800]
        ]
    ]
];
