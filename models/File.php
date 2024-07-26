<?php
namespace Sommer\Multidb\Models;

use System\Models\File as SystemFile;
use Winter\Storm\Support\Facades\Url;
use Illuminate\Support\Facades\Config;

/**
 * @class File Model
 * @package Sommer\MultiDB\Models
 * @since 1.0.0
 * @author Roni Sommerfeld<roni@4tech.mobi>
 *
 * @see \System\Models\File
 */
class File extends SystemFile
{

    use \Sommer\MultiDB\Traits\UsesTenantConnection;

    /**
     * Define the internal storage path, override this method to define.
     * @override
     * @return string
     */
    public function getStorageDirectory()
    {
        $databaseName = $this->getDatabaseName() ?? '';

        if ($this->isPublic()) {
            return $databaseName . 'uploads/public/';
        }

        return $databaseName . 'uploads/protected/';
    }

    /**
     * Define the public address for the storage path.
     * @override
     */
    public function getPublicPath()
    {
        $databaseName = $this->getDatabaseName() ?? null;

        if (!$databaseName) {
            return parent::getPublicPath();
        }

        $uploadsPath = Config::get('cms.storage.uploads.path', '/storage/app/uploads');
        $uploadsPath = preg_replace('/(.*?)(\/?uploads)/', '$1/' . $databaseName . '/uploads', $uploadsPath);

        if ($this->isPublic()) {
            $uploadsPath .= '/public';
        } else {
            $uploadsPath .= '/protected';
        }

        return Url::asset($uploadsPath) . '/';
    }

    /**
     * If working with local storage, determine the absolute local path.
     * @override
     */
    protected function getLocalRootPath(): string
    {
        $path         = null;
        $databaseName = $this->getDatabaseName();

        if ($this->isLocalStorage()) {
            $path = $this->getDisk()->getConfig()['root'] ?? null;
        }

        if (is_null($path)) {
            $path = storage_path('app');
        }

        if ($databaseName) {
            $path = "{$path}/{$databaseName}";
        }

        return $path;
    }
}
