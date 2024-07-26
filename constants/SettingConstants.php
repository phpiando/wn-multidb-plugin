<?php namespace Sommer\MultiDB\Constants;

/**
 * @class Constants for the plugin settings
 * @package Sommer\MultiDB\Constants
 * @since 1.0.0
 * @author Roni Sommerfeld<roni@4tech.mobi> 
 */
class SettingConstants
{
    /**
     * The settings code for the plugin
     * @since 1.0.0
     * @var string
     */
    const SETTINGS_CODE = 'sommer_multidb_settings';
    
    /**
     * The options for the database name field
     * @since 1.0.0
     * @var array
     */
    const DB_NAME_OPTIONS = [
        'db_code' => 'sommer.multidb::lang.options.db_code',
        'db_name' => 'sommer.multidb::lang.options.db_name',
    ];  
}