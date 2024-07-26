<?php namespace Sommer\MultiDB\Models;

use Winter\Storm\Database\Model;

/**
 * @class MigrationError Model
 * @package Sommer\MultiDB\Models
 * @since 1.0.0
 * @author Roni Sommerfeld<roni@4tech.mobi>
 *
 * @property integer $id
 * @property string $database_name
 * @property string $type
 * @property string $version
 * @property string $detail
 * @property string $error
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class MigrationError extends Model
{
    use \Winter\Storm\Database\Traits\Validation;
    use \Sommer\MultiDB\Traits\UsesMainConnection;

    /**
     * @var string The database table used by the model.
     */
    public $table = 'sommer_multidb_migration_errors';

    /**
     * @var array Validation rules
     */
    public $rules = [
    ];
}
