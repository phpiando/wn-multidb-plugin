<?php

/**
 * Config database for the plugin
 * @package Sommer\MultiDB\Config
 * @since 1.0.0
 * @author Roni Sommerfeld<roni@4tech.mobi>
 */

use Sommer\MultiDB\Constants\TenantConstants;

return [
    /**
     * The connection for the database
     * @since 1.0.0
     * @var string
     */
    'multidb_connection' => env('MULTIDB_CONNECTION', 'multidb'),

    /**
     * The queue for the database
     * @since 1.0.0
     * @var string
     */
    'multidb_queue' => env('MULTIDB_QUEUE', TenantConstants::QUEUE_DEFAULT),

    /**
     * The configuration for the database
     * @since 1.0.0
     * @var array
     */
    'multidb_configuration' => [
        'driver' => 'mysql',
        'host' => env('MULTIDB_HOST', env('DB_HOST', 'localhost')),
        'port' => env('MULTIDB_PORT', env('DB_PORT', '3306')),
        'database' => env('MULTIDB_DATABASE', 'forge'),
        'username' => env('MULTIDB_USERNAME', env('DB_USERNAME', 'forge')),
        'password' => env('MULTIDB_PASSWORD', env('DB_PASSWORD', '')),
        'charset' => env('MULTIDB_CHARSET', env('DB_CHARSET', 'utf8mb4')),
        'collation' => env('MULTIDB_COLLATION', env('DB_COLLATION', 'utf8mb4_unicode_ci')),
        'prefix' => '', // Adicione qualquer prefixo de tabela, se necessário
        'strict' => true,
        'engine' => null,
        'options' => extension_loaded('pdo_mysql') ? array_filter([
            PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
        ]) : [],
    ]
];