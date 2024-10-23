<?php
namespace Sommer\MultiDB\Controllers;

use Exception;
use Backend\Classes\Controller;
use Backend\Facades\BackendMenu;
use Sommer\MultiDB\Models\Tenant;
use System\Classes\SettingsManager;
use Illuminate\Support\Facades\Request;
use Winter\Storm\Support\Facades\Flash;
use Sommer\MultiDB\Constants\TenantConstants;
use Sommer\Multidb\Models\Setting;
use Sommer\Multidb\Services\TenantInstanceService;

/**
 * @class Tenants Controller
 * @package Sommer\MultiDB\Controllers
 * @since 1.0.0
 * @author Roni Sommerfeld<roni@4tech.mobi>
 */
class Tenants extends Controller
{
    /**
     * @var array
     */
    public $implement = [
        'Backend\Behaviors\ListController',
        'Backend\Behaviors\FormController',
    ];

    /**
     * @var string
     */
    public $listConfig = 'config_list.yaml';
    /**
     * @var string
     */
    public $formConfig = 'config_form.yaml';

    /**
     * @var array
     */
    public $requiredPermissions = [
        'sommer.multidb.tenants',
    ];

    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('Winter.System', 'system', 'settings');
        SettingsManager::setContext('Sommer.Multidb', 'sommer_multidb_tenants');
    }

    /**
     * Force update selected tenants
     * @since 1.0.0
     * @throws Exception if an error occurs
     * @return void
     */
    public function onForceUpdateSelected(): void
    {
        try {
            $ids     = post('checked');
            $tenants = Tenant::whereIn('id', $ids)->get();

            $databaseOnQueue = Setting::instance()->get('database_queue_on_create');
            $service = new TenantInstanceService();

            foreach ($tenants as $tenant) {
                if ($databaseOnQueue) {
                    $service->addQueueToUpdate($tenant->id);
                    continue;
                }

                $service->setTenant($tenant)->forceUpdate();
            }

            Flash::success(trans('sommer.multidb::lang.messages.success.tenant_updated'));
        } catch (Exception $ex) {
            Flash::error($ex->getMessage());
        }
    }

    /**
     * Force update all tenants
     * @since 1.2.0
     * @return void
     */
    public function onForceUpdateAll(): void{
        try {
            $tenants = Tenant::where('has_updates', true)->get();

            $databaseOnQueue = Setting::instance()->get('database_queue_on_create');
            $service = new TenantInstanceService();

            foreach ($tenants as $tenant) {
                if ($databaseOnQueue) {
                    $service->addQueueToUpdate($tenant->id);
                    continue;
                }

                $service->setTenant($tenant)->forceUpdate();
            }

            Flash::success(trans('sommer.multidb::lang.messages.success.tenant_updated'));
        } catch (\Throwable $th) {
            Flash::error(message: $th->getMessage());
        }
    }

    /**
     * Extend the form fields
     *
     * @param Form $form
     * @return void
     */
    public function formExtendFields($form)
    {
        if (Request::segment(TenantConstants::SEGMENT_UPDATE) !== TenantConstants::CONTEXT_UPDATE) {
            return;
        }

        $form->addFields(TenantConstants::CONTEXT_UPDATE_FIELDS);
    }
}
