<?php namespace Sommer\MultiDB\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @class CacheTenant
 * @package Sommer\MultiDB\Facades
 * @since 1.0.0
 * @author Roni Sommerfeld<roni@4tech.mobi>
 */
class CacheTenant extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'cache.tenant';
    }
}
