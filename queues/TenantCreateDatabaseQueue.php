<?php
namespace Sommer\Multidb\Queues;

use Sommer\MultiDB\Models\Tenant;
use Illuminate\Support\Facades\Queue;
use Sommer\MultiDB\Constants\TenantConstants;
use Sommer\Multidb\Services\TenantInstanceService;

/**
 * @class Queue to create a new database
 * @package Sommer\MultiDB\Models
 * @since 1.0.0
 * @author Roni Sommerfeld<roni@4tech.mobi>
 */
class TenantCreateDatabaseQueue
{
    /**
     * Register a new queue to
     * create a new database
     *
     * @param array $data
     * @return void
     */
    public function registerQueue(array $data): void
    {
        Queue::push(get_class($this), $data, config('sommer.multidb::database.multidb_queue'));
    }

    /**
     * Dispara e executa a queue
     *
     * @param object $job
     * @param array $data
     * @return void
     */
    public function fire(object $job, array $data): void
    {
        try {
            $tenant = Tenant::find($data['tenant_id']);
            $immediate = $data['immediate'] ?? false;

            (new TenantInstanceService(tenant: $tenant, immediateUp: $immediate))->startCreateDatabase();

            sleep(TenantConstants::TIMEOUT_AFTER_CREATE_QUEUE);

            $job->delete();
        } catch (\Throwable $ex) {
            $job->release($job->attempts() * TenantConstants::TIMEOUT_RELEASE_QUEUE);
        }
    }
}
