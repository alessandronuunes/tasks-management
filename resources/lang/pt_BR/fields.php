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
            'tags' => 'Selecione as tags'
        ]
    ],
    'custom_fields' => [
        'code' => 'Código',
        'type' => 'Tipo',
        'options' => 'Opções',
        'is_required' => 'Obrigatório',
        'sort_order' => 'Ordem',
        'types' => [
            'text' => 'Campo de Texto',
            'select' => 'Seleção',
        ],
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
];