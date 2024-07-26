<?php
namespace Sommer\MultiDB\Classes;

use Illuminate\Support\Facades\Cache;
use Sommer\MultiDB\Classes\TenantManager;
use Illuminate\Cache\CacheManager as BaseCacheManager;

/**
 * @class TenatnCacheManager
 * @package Sommer\MultiDB\Classes
 * @extends BaseCacheManager
 * @since 1.0.0
 * @author Roni Sommerfeld<roni@4tech.mobi>
 */
class TenantCacheManager extends BaseCacheManager
{
    /**
     * @param $app
     */
    public function __construct($app)
    {
        parent::__construct($app);
    }

    /**
     * Get a cache store instance by name.
     * @since 1.0.0
     * @param  string|null  $name
     * @return \Illuminate\Contracts\Cache\Repository
     */
    public function store($name = null)
    {
        $store = parent::store($name);
        return $this->addTenantTag($store);
    }

    /**
     * Add the tenant tag to the cache store.
     * @since 1.0.0
     * @param  \Illuminate\Contracts\Cache\Repository  $store
     * @return \Illuminate\Contracts\Cache\Repository
     */
    protected function addTenantTag($store)
    {
        $tenant = TenantManager::getTenant();
        if ($tenant) {
            $store = $store->tags([$tenant->database_name]);
        }
        return $store;
    }
}
