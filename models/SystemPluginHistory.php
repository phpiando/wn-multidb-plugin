<?php
namespace Sommer\Multidb\Models;

use Winter\Storm\Database\Model;

/**
 * @class SystemPluginHistory Model
 * @package Sommer\MultiDB\Models
 * @since 1.0.0
 * @author Roni Sommerfeld<roni@4tech.mobi>
 */
class SystemPluginHistory extends Model
{
    use \Sommer\MultiDB\Traits\UsesTenantConnection;

    /**
     * @var string
     */
    public $table = 'system_plugin_history';

    /**
     * @var bool Disable model timestamps.
     */
    public $timestamps = false;
}
