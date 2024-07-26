<?php
namespace Sommer\MultiDB\Behaviors;

use Illuminate\Http\RedirectResponse;
use Sommer\MultiDB\Models\Tenant;
use Illuminate\Support\Facades\View;
use Winter\Storm\Database\Collection;
use Winter\Storm\Support\Facades\Flash;
use Sommer\MultiDB\Classes\TenantManager;
use Sommer\MultiDB\Classes\TenantSession;

/**
 * @class TenantSelectionBehavior
 * @package Sommer\MultiDB\Behaviors
 * @since 1.0.0
 * @author Roni Sommerfeld <roni@4tech.mobi>
 */
class TenantSelectionBehavior extends \Winter\Storm\Extension\ExtensionBase
{
    /**
     * @since 1.0.0
     * @var mixed
     */
    protected mixed $parent;
    /**
     * @since 1.0.0
     * @var Tenant|null
     */
    protected ?Tenant $tenant = null;
    /**
     * @since 1.0.0
     * @var TenantSession
     */
    protected TenantSession $tenantSession;

    /**
     * @since 1.0.0
     * @param mixed $parent
     */
    public function __construct(mixed $parent)
    {
        $this->parent = $parent;
        $this->tenantSession = TenantSession::instance();
        $this->prepareButtonTenant();
        $this->startTenantSelection();
    }

    /**
     * Initialize the tenant selection process
     * @since 1.0.0
     * @return void
     */
    public function startTenantSelection(): void
    {
        if (!$this->getSessionTenantId()) {
            $this->prepareModalTenant();
            return;
        }

        $this->setTenant(Tenant::find($this->getSessionTenantId()));
        $this->startTenantInstance();
    }

    /**
     * Prepare the button to select a tenant
     * @since 1.0.0
     * @return void
     */
    private function prepareButtonTenant(): void
    {
        $this->parent->addJs('/plugins/sommer/multidb/assets/js/button-tenant-selection.js');
    }

    /**
     * Prepare the modal to select a tenant
     * @since 1.0.0
     * @return void
     */
    private function prepareModalTenant(): void
    {
        $this->parent->addJs('/plugins/sommer/multidb/assets/js/auto-popup-tenant-selection.js');
    }

    /**
     * Modal tenant selection
     * @since 1.0.0
     * @return array
     */
    public function onModalTenantSelection(): array
    {
        $tenants = $this->getTenants();
        $view    = View::make('sommer.multidb::modals.modal-tenant-selection', [
            'tenants' => $tenants,
        ])->render();

        return [
            'result' => $view,
        ];
    }

    /**
     * Select a tenant
     * @since 1.0.0
     * @return void
     */
    public function onSelectTenant(): RedirectResponse
    {
        $tenantId = post('tenant_id');
        $this->setSessionTenantId($tenantId);
        $this->setTenant(Tenant::find($tenantId));
        $this->startTenantInstance();

        Flash::success(trans('sommer.multidb::lang.messages.success.tenant_selected'));

        return redirect()->refresh();
    }

    /**
     * Start a tenant configuration
     * @since 1.0.0
     * @return void
     */
    public function startTenantInstance(): void
    {
        app(TenantManager::class)->startTenantConnection($this->tenant);
    }

    /**
     * Get the tenants
     * @since 1.0.0
     * @return Collection
     */
    public function getTenants(): Collection
    {
        return Tenant::isActive()->get();
    }

    /**
     * Set the tenant id in the session
     *
     * @since 1.0.0
     * @param int $tenantId The tenant id
     * @return void
     */
    private function setSessionTenantId(int $tenantId): void
    {
        $this->tenantSession->setTenantId($tenantId);
    }

    /**
     * Get the tenant id from the session
     * @since 1.0.0
     * @return int|null
     */
    private function getSessionTenantId(): int | null
    {
        return $this->tenantSession->getTenantId();
    }

    /**
     * Set the tenant
     *
     * @since 1.0.0
     * @param Tenant $tenant
     * @return void
     */
    private function setTenant(?Tenant $tenant): void
    {
        $this->tenant = $tenant;
    }
}
