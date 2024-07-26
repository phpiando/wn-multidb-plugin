<?php
namespace Sommer\MultiDB\Classes;

/**
 * @class TenantSession
 * @package Sommer\MultiDB\Classes
 * @since 1.0.0
 * @author Roni Sommerfeld<roni@4tech.mobi>
 */
class TenantSession
{
    /**
     * @var self|null
     */
    protected static ?self $singleton = null;
    /**
     * @var string
     */
    public const SESSION_KEY = 'session_tenant_id';

    /**
     * Set the tenant id in the session
     * @since 1.0.0
     * @param mixed $tenantId The tenant id
     * @return void
     */
    public function setTenantId(mixed $tenantId): void
    {
        session()->put(self::SESSION_KEY, $tenantId);
    }

    /**
     * Get the tenant id from the session
     * @since 1.0.0
     * @return mixed
     */
    public function getTenantId(): mixed
    {
        return session()->get(self::SESSION_KEY);
    }

    /**
     * Create a singleton instance of the class
     * @since 1.0.0
     * @return mixed
     */
    public static function instance(): self
    {
        if (null === self::$singleton) {
            self::$singleton = new self();
        }

        return self::$singleton;
    }
}
