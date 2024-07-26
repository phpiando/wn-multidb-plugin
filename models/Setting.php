<?php namespace Sommer\Multidb\Models;

use Sommer\MultiDB\Constants\SettingConstants;
use Winter\Storm\Database\Model;
use System\Models\PluginVersion;

/**
 * @class Setting Model
 * @package Sommer\MultiDB\Models
 * @since 1.0.0
 * @author Roni Sommerfeld<roni@4tech.mobi>
 *
 * @property string $database_prefix
 * @property string $database_rule_to_name
 * @property array $plugins
 * @property bool $database_queue_on_create
 */
class Setting extends Model
{
    use \Sommer\MultiDB\Traits\UsesMainConnection;

    /**
     * Behaviors implemented by this model.
     *
     * @var array
     */
    public $implement = ['System.Behaviors.SettingsModel'];

    /**
     * A unique code for this model.
     *
     * @var string
     */
    public $settingsCode = SettingConstants::SETTINGS_CODE;

    /**
     * Reference to field configuration.
     *
     * @var string
     */
    public $settingsFields = 'fields.yaml';

    /**
     * Returns the options for the database name fields.
     *
     * @return array
     */
    public function getDatabaseRuleToNameOptions(): array
    {
        return SettingConstants::DB_NAME_OPTIONS;
    }

    public function getPluginsOptions(): array
    {
        return PluginVersion::lists('code', 'code');
    }
}
