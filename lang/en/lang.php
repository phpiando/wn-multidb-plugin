<?php return [
    'plugin' => [
        'name' => 'Sommer MultiDB',
        'description' => '',
    ],
    'fields' => [
        'tenant' => [
            'name' => 'Database Identification',
            'description' => 'Database Description',
            'comments' => [
                'name' => 'This is the name of the database that will be concatenated with the prefix, considering that the name has a maximum length of 63 characters.',
                'description' => 'Database description, a brief description or name to identify the database',
                'is_active' => 'Indicates if this database will be active',
                'has_updates' => 'Will this database receive automatic migrations updates?',
                'database_name' => 'If this field is empty, a name will be created based on the prefix + database description. Once created, the database name cannot be changed.',
                'has_database_created' => 'If the database has already been created, check this option to prevent the system from trying to create it again.',
            ],
            'is_active' => 'Is it active?',
            'has_updates' => 'Receives automatic updates?',
            'database_name' => 'Database Name',
            'has_database_created' => 'Database already created?',
        ],
        'settings' => [
            'database_prefix' => 'Database Prefix',
            'database_rule_to_name' => 'Rule for Database Name',
            'database_name_limit' => '64-character limit for the database name',
            'database_queue_on_create' => 'Add to database creation queue',
            'plugins' => 'Plugins to be synchronized',
            'comments' => [
                'plugins' => 'Identify which plugins will be synchronized with the databases',
                'database_queue_on_create' => 'The server needs to have the queue service configured for this option to work correctly',
            ],
        ],
    ],
    'permissions' => [
        'multidb' => [
            'tenants' => 'Manage database records',
            'settings' => 'Manage MultiDB options and settings',
        ],
    ],
    'menus' => [
        'settings' => [
            'label' => 'Manage Settings',
            'description' => 'Manage MultiDB settings',
        ],
        'tenants' => [
            'label' => 'Manage Databases',
            'description' => 'Manage database settings and data',
        ],
    ],
    'options' => [
        'db_code' => 'Hash Code',
        'db_name' => 'Use database name',
    ],
    'buttons' => [
        'force_selected_tenant' => 'Update Selected Tenant',
        'force_all_tenants' => 'Update All Tenants',
        'comments' => [
            'force_selected_tenant' => 'Do you really want to update the selected tenants?',
            'force_all_tenants' => 'Do you really want to update all tenants?',
        ],
    ],
    'messages' => [
        'errors' => [
            'tenant_not_found' => 'Tenant not found',
        ],
        'success' => [
            'tenant_selected' => 'Tenant successfully selected, changing the view to the selected tenant.',
        ],
    ],
    'modals' => [
        'tenant_selection' => [
            'title' => 'Tenant Selection',
            'description' => 'To continue, you must select at least one tenant to display its data.',
            'confirm_selection' => 'Do you really want to select this tenant?',
            'select_tenant' => 'Select a tenant',
            'button_cancel' => 'Cancel',
            'button_confirm' => 'Confirm',
            'button_loading' => 'Loading...',
        ],
    ]
];
