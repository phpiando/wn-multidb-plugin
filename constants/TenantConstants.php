<?php namespace Sommer\MultiDB\Constants;

/**
 * @class Constants for the Model/Controller Tenant
 * @package Sommer\MultiDB\Constants
 * @since 1.0.0
 * @author Roni Sommerfeld<roni@4tech.mobi> 
 */
class TenantConstants
{
    /**
     * The context for the create action
     * @since 1.0.0
     * @var string
     */
    public const CONTEXT_UPDATE = 'update';

    /**
     * The database code length
     * @since 1.0.0
     * @var int
     */
    public const DATABASE_CODE_LENGTH = 15;

    /**
     * The context for the create action
     * @since 1.0.0
     * @var string
     */
    public const SEGMENT_UPDATE = 5;

    /**
     * The database rule to name
     * @since 1.0.0
     * @var string
     */
    public const DATABASE_RULE_NAME_DB_CODE = 'db_code';

    /**
     * The database name limit
     * @since 1.0.0
     * @var int
     */
    public const DATABASE_NAME_LIMIT = 64;

    /**
     * The timeout for the release queue
     * @since 1.0.0
     * @var int
     */
    public const TIMEOUT_RELEASE_QUEUE = 5;

    /**
     * The default queue
     * @since 1.0.0
     * @var string
     */
    public const QUEUE_DEFAULT = 'default';

    /**
     * The fields for the update context
     * @since 1.0.0
     * @var array
     */
    public const CONTEXT_UPDATE_FIELDS = [
        'database_name' => [
            'label' => 'sommer.multidb::lang.fields.tenant.database_name',
            'span' => 'auto',
            'type' => 'text',
            'comment' => 'sommer.multidb::lang.fields.tenant.comments.database_name',
            'disabled' => 1,
        ]
    ];
}