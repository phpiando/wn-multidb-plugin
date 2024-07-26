<form class="form-elements" role="form">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                &times;
            </button>
            <h4 class="modal-title">
                <?= trans("sommer.multidb::lang.modals.tenant_selection.title") ?>
            </h4>
        </div>
        <div class="modal-body" style="height: 150px">
            <p>
                <?= trans("sommer.multidb::lang.modals.tenant_selection.description") ?>
            </p>
            <div class="form-group dropdown-field span-full">
                <label>Website</label>
                <select class="form-control custom-select" name="tenant_id">
                    <option value=""><?= trans("sommer.multidb::lang.modals.tenant_selection.select_tenant") ?></option>

                    <?php foreach ($tenants as $u): ?>
                        <option value="<?= $u->id ?>"><?= $u->description ?? $u->name ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div class="modal-footer">
            <div class="loading-indicator-container">
                <button type="button" class="btn btn-default" id="dismiss-modal" data-dismiss="modal">
                <?= trans("sommer.multidb::lang.modals.tenant_selection.button_cancel") ?>
                </button>
                <button type="submit"
                    data-request="onSelectTenant"
                    data-request-validate
                    data-request-flash
                    data-request-confirm="<?= trans("sommer.multidb::lang.modals.tenant_selection.confirm_selection") ?>"
                    data-load-indicator="<?= trans("sommer.multidb::lang.modals.tenant_selection.button_loading") ?>"
                    data-request-success="$('#dismiss-modal').trigger('click')" class="btn btn-primary">
                    <?= trans("sommer.multidb::lang.modals.tenant_selection.button_confirm") ?>
                </button>
            </div>
        </div>
    </div>
</form>