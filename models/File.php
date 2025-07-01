<?php
namespace Sommer\Multidb\Models;

use Storage;
use Backend\Controllers\Files;
use Winter\Storm\Support\Facades\Url;
use Illuminate\Support\Facades\Config;
use Winter\Storm\Database\Attach\File as FileBase;

/**
 * @class File Model
 * @package Sommer\MultiDB\Models
 * @since 1.0.0
 * @author Roni Sommerfeld<roni@4tech.mobi>
 *
 * @override \System\Models\File
 * @see \System\Models\File
 */
class File extends FileBase
{
    use \Sommer\MultiDB\Traits\UsesTenantConnection;

    /**
     * @var string The database table used by the model.
     */
    protected $table = 'system_files';

    /**
     * Define the public address for the storage path.
     * @override
     */
    public function getPublicPath()
    {
        $databaseName = $this->getDatabaseName() ?? null;

        if (!$databaseName) {
            return $this->getPublicPathDefault();
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
     * Define the public address for the storage path.
     */
    public function getPublicPathDefault()
    {
        $uploadsPath = Config::get('cms.storage.uploads.path', '/storage/app/uploads');

        if ($this->isPublic()) {
            $uploadsPath .= '/public';
        }
        else {
            $uploadsPath .= '/protected';
        }

        return Url::asset($uploadsPath) . '/';
    }


    /**
     * {@inheritDoc}
     */
    public function getThumb($width, $height, $options = [])
    {
        $url = '';
        $width = !empty($width) ? $width : 0;
        $height = !empty($height) ? $height : 0;

        if (!$this->isPublic() && class_exists(Files::class)) {
            $options = $this->getDefaultThumbOptions($options);
            // Ensure that the thumb exists first
            parent::getThumb($width, $height, $options);

            // Return the Files controller handler for the URL
            $url = Files::getThumbUrl($this, $width, $height, $options);
        } else {
            $url = parent::getThumb($width, $height, $options);
        }

        return $url;
    }

    /**
     * {@inheritDoc}
     */
    public function getPath($fileName = null)
    {
        $url = '';
        if (!$this->isPublic() && class_exists(Files::class)) {
            $url = Files::getDownloadUrl($this);
        } else {
            $url = parent::getPath($fileName);
        }

        return $url;
    }

    /**
     * Define the internal storage path.
     */
    public function getStorageDirectory()
    {
        if($this->getDatabaseName()){
            return $this->getStorageDatabase();
        }

        $uploadsFolder = Config::get('cms.storage.uploads.folder');

        if ($this->isPublic()) {
            return $uploadsFolder . '/public/';
        }

        return $uploadsFolder . '/protected/';
    }

    public function getStorageDatabase(){
        $databaseName = $this->getDatabaseName() ?? '';

        if ($this->isPublic()) {
            return $databaseName . '/uploads/public/';
        }

        return $databaseName . '/uploads/protected/';
    }

    /**
     * Returns the storage disk the file is stored on
     * @return FilesystemAdapter
     */
    public function getDisk()
    {
        return Storage::disk(Config::get('cms.storage.uploads.disk'));
    }
}