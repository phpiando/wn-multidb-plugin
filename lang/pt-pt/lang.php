<?php return [
    'plugin' => [
        'name' => 'Sommer MultiDB',
        'description' => '',
    ],
    'fields' => [
        'tenant' => [
            'name' => 'Identificação do banco de dados',
            'description' => 'Descrição do banco de dados',
            'comments' => [
                'name' => 'Esse é o nome do banco que será concatenado com o prefixo, precisa levar em consideração que o nome tem o tamanho máximo de 63 caracteres.',
                'description' => 'Descrição do banco de dados, uma breve descrição ou nome para identificar o banco de dados',
                'is_active' => 'Identifica se esse banco de dados estará ativo',
                'has_updates' => 'Esse banco receberá atualizações automáticas das migrations?',
                'database_name' => 'Se este campo estiver vazio será criado um nome com base no prefix + descrição do banco. Uma vez criado o banco não tem como alterar o nome.',
                'has_database_created' => 'Se o banco de dados já foi criado, marcar essa opção para evitar que o sistema tente criar novamente.',
            ],
            'is_active' => 'Está ativo?',
            'has_updates' => 'Recebe atualizações automáticas?',
            'database_name' => 'Nome do banco de dados',
            'has_database_created' => 'Banco de dados já foi criado?',
        ],
        'settings' => [
            'database_prefix' => 'Prefixo do banco',
            'database_rule_to_name' => 'Regra para o nome do banco de dados',
            'database_name_limit' => 'Limite de 64 caracteres para o nome do banco de dados',
            'database_queue_on_create' => 'Adicionar na fila de criação do banco de dados',
            'plugins' => 'Plugins que serão sincronizados',
            'comments' => [
                'plugins' => 'Identifique quais plugins que serão sincronizados com os banco de dados',
                'database_queue_on_create' => 'É necessário que o servidor tenha o serviço de fila configurado para que essa opção funcione corretamente',
            ],
        ],
    ],
    'permissions' => [
        'multidb' => [
            'tenants' => 'Gerir o registro de banco de dados',
            'settings' => 'Gerir as opções e configurações do MultiDB',
        ],
    ],
    'menus' => [
        'settings' => [
            'label' => 'Gerir as Configurações',
            'description' => 'Gerir as configurações do MultiDB',
        ],
        'tenants' => [
            'label' => 'Gerir Databases',
            'description' => 'Gerir configurações e dados dos bancos de dados',
        ],
    ],
    'options' => [
        'db_code' => 'Codigo Hash',
        'db_name' => 'Usar o nome do banco de dados',
    ],
    'buttons' => [
        'force_selected_tenant' => 'Atualizar Tenant Selecionado',
        'force_all_tenants' => 'Atualizar todos os tenants',
        'comments' => [
            'force_selected_tenant' => 'Deseja realmente atualizar os tenants selecioandos?',
            'force_all_tenants' => 'Deseja realmente atualizar todos os tenants?',
        ],
    ],
    'messages' => [
        'errors' => [
            'tenant_not_found' => 'Tenant não encontrado',
        ],
        'success' => [
            'tenant_selected' => 'Tenant selecionado com sucesso, alterando a visualização para o tenant selecionado.',
        ],
    ],
    'modals' => [
        'tenant_selection' => [
            'title' => 'Seleção de Tenants',
            'description' => 'Para continuar é necessário selecionar ao menos um tenant para exibir os dados do mesmo.',
            'confirm_selection' => 'Deseja realmente selecionar esse tenant?',
            'select_tenant' => 'Selecione um tenant',
            'button_cancel' => 'Cancelar',
            'button_confirm' => 'Confirmar',
            'button_loading' => 'Carregando...',
        ],
    ]
];