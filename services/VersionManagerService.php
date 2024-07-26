<?php
namespace Sommer\MultiDB\Services;

use System\Classes\VersionManager;

/**
 * @class VersionManagerService
 * @package Sommer\MultiDB\Services
 * @since 1.0.0
 * @author Roni Sommerfeld<roni@4tech.mobi>
 */
class VersionManagerService extends VersionManager
{
    /**
     * Reset the caches
     *
     * @return void
     */
    public function resetCaches()
    {
        $this->databaseVersions = null;
        $this->databaseHistory  = [];
    }
}
