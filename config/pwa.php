<?php
return [
    'install-button' => true,

    'manifest' => [
        'name' => 'Laravel PWA',
        'short_name' => 'LPT',
        'id' => '/',
         "start_url"=> "/",
        'background_color' => '#000000',
        'description' => 'A Progressive Web Application setup for Laravel projects.',
        'display' => 'standalone',
        'theme_color' => '#000000',

        'icons' => [

            [
                'src' => '/store.png',
                'sizes' => '512x512',
                'type' => 'image/png'

            ]
        ],
    ],

    'debug' => env('APP_DEBUG', false),
    'livewire-app' => false,
];