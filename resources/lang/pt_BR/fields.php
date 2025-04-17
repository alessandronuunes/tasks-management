<?php

declare(strict_types=1);

return [
    'common' => [
        'name' => 'Nome',
        'description' => 'Descrição',
        'created_at' => 'Criado em',
        'updated_at' => 'Atualizado em',
    ],
    'tasks' => [
        'status' => 'Status',
        'priority' => 'Prioridade',
        'type' => 'Tipo',
        'users' => 'Responsáveis',
        'starts_at' => 'Início',
        'ends_at' => 'Fim',
        'assigned_users' => 'Usuários Atribuídos',
        'tags' => 'Tags',
        'attachments' => 'Anexos',
        'sections' => [
            'task_data' => 'Dados da Tarefa',
            'properties' => 'Propriedades'
        ],
        'placeholders' => [
            'name' => 'Digite o nome da tarefa',
            'description' => 'Digite a descrição da tarefa',
            'status' => 'Selecione o status',
            'priority' => 'Selecione a prioridade',
            'type' => 'Selecione o tipo',
            'users' => 'Selecione os usuários',
            'tags' => 'Selecione as tags'
        ]
    ],
    'custom_fields' => [
        'code' => 'Código',
        'type' => 'Tipo do Campo',
        'options' => 'Opções',
        'option_label' => 'Rótulo da Opção',
        'option_value' => 'Valor da Opção',
        'is_required' => 'Obrigatório',
        'sort_order' => 'Ordem',
        'types' => [
            'text' => 'Texto',
            'select' => 'Seleção',
        ],
        'placeholder' => 'Placeholder',
        'help_text' => 'Texto de Ajuda',
        'hint' => 'Dica',
        'settings' => 'Configurações',
        'preview' => 'Prévia',
        'preview_label' => 'Prévia do Campo',
    ],
    'task_tags' => [
        'color' => 'Cor',
    ],
    'comments' => [
        'title' => 'Comentários',
        'content' => 'Conteúdo',
        'user' => 'Usuário',
        'created_at' => 'Criado em',
        'actions' => [
            'create' => 'Adicionar comentário',
        ],
        'modal' => [
            'new' => 'Novo Comentário',
        ],
    ],
    'audits' => [
        'event' => 'Evento',
        'user' => 'Usuário',
        'created_at' => 'Data',
        'ip_address' => 'IP',
        'field' => 'Campo',
        'old_value' => 'Valor Anterior',
        'new_value' => 'Novo Valor',
        'user_agent' => 'Agente do Usuário',
        'url' => 'URL',
        'sections' => [
            'details' => 'Detalhes do Log',
            'changes' => 'Alterações',
        ],
        'columns' => [
            'field' => 'Campo',
            'old_value' => 'Valor Anterior',
            'new_value' => 'Novo Valor',
        ],
        'messages' => [
            'no_changes' => 'Nenhuma alteração registrada',
            'system' => 'Sistema',
        ],
    ],
];