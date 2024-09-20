<?php namespace Sommer\MultiDB\Models;

use Sommer\MultiDB\Services\TenantModelService;
use Winter\Storm\Database\Model;
use Winter\Storm\Database\Builder;

/**
 * @class Tenant Model
 * @package Sommer\MultiDB\Models
 * @since 1.0.0
 * @author Roni Sommerfeld<roni@4tech.mobi>
 *
 * @property string $name
 * @property string $description
 * @property boolean $is_active
 * @property boolean $has_updates
 * @property boolean $has_waiting_sync
 * @property boolean $has_custom_auth_database
 * @property boolean $has_database_created
 * @property string $database_name
 * @property string $database_host
 * @property integer $database_port
 * @property string $database_user
 * @property string $database_pass
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon $deleted_at
 *
 * @method static Builder isActive()
 */
class Tenant extends Model
{
    use \Winter\Storm\Database\Traits\Validation;
    use \Winter\Storm\Database\Traits\SoftDelete;
    use \Sommer\MultiDB\Traits\UsesMainConnection;

    protected $dates = ['deleted_at'];

    /**
     * @var string The database table used by the model.
     */
    public $table = 'sommer_multidb_tenants';

    /**
     * @var array Validation rules
     */
    public $rules = [
        'name' => 'required',
    ];

    public function beforeCreate(): void
    {
        (new TenantModelService)->beforeCreate($this);
    }

    public function afterCreate(): void
    {
        (new TenantModelService)->afterCreate($this);
    }

    /**
     * Scope a query to only include active tenants.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeIsActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to only include tenants with updates.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeHasUpdates(Builder $query): Builder
    {
        return $query->where('has_updates', true);
    }
}
