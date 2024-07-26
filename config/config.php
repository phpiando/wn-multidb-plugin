<?php 

/**
 * Config file for the plugin
 * @package Sommer\MultiDB\Config
 * @since 1.0.0
 * @author Roni Sommerfeld<roni@4tech.mobi>
 */
return [
    /**
     * Database prefix default
     * @since 1.0.0
     * @var string
     */
    'database_prefix' => 'hice_',

    /**
     * database rule to name
     * @since 1.0.0
     * @var string
     */
    'database_rule_to_name' => 'db_code',

    /**
     * Database path with migrations and seeders
     * @since 1.0.0
     * @var string
     */
    'database_migration_path' => plugins_path('sommer/multidb/database/migrations'),
];