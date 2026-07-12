<?php

$_ENV = parse_ini_file(__DIR__ . "/.env");

return [
    'paths' => [
        'migrations' => '%%PHINX_CONFIG_DIR%%/db/migrations',
        'seeds' => '%%PHINX_CONFIG_DIR%%/db/seeds',
    ],

    'environments' => [
        'default_migration_table' => 'phinxlog',
        'default_environment' => 'default',
        'default' => [
            'adapter' => $_ENV['DB_ADAPTER'],
            'host' => $_ENV['DB_SERVER_NAME'],
            'name' => $_ENV['DB_NAME'],
            'user' => $_ENV['DB_USERNAME'],
            'pass' => $_ENV['DB_PASSWORD'],
            'port' => $_ENV['DB_PORT'],
        ]
    ],

    'version_order' => 'creation',
];