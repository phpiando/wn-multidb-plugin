<?php namespace Sommer\MultiDB\Models;

use System\Models\PluginVersion;

class SystemPluginVersion extends PluginVersion{
    use \Sommer\MultiDB\Traits\UsesTenantConnection;

}