# Laravel Tasks Management

Sistema de gerenciamento de tarefas para Laravel com suporte a Filament, que inclui recursos de atribuição de usuários, comentários, prioridades, status e tipos de tarefas.

## Características

- ✨ Interface moderna com Filament 3
- 🔄 Sistema de status e prioridades
- 👥 Atribuição múltipla de usuários
- 💬 Sistema de comentários
- 🔔 Notificações em tempo real
- 📱 Interface responsiva
- 🏢 Suporte opcional a multitenancy

## Requisitos

- PHP 8.2 ou superior
- Laravel 11.x
- Filament 3.x

## Instalação

1. Adicione o repositório ao seu composer.json:
```json
"repositories": [
    {
        "type": "vcs",
        "url": "https://github.com/alessandronuunes/tasks-management"
    }
] ```
2. Instale o pacote:
```bash
composer require alessandronuunes/tasks-management ```
3. Execute as migrações:
```bash
php artisan migrate ```
4. Publique os assets:
```bash
php artisan vendor:publish --tag=public --force ```

5. Publique as configurações:
```bash
php artisan vendor:publish --tag=config --force ``` 

Configuração
O pacote inclui uma configuração que pode ser modificada no arquivo config/tasks-management.php.

Uso
O package adiciona automaticamente um novo recurso "Tarefas" ao seu painel Filament.
Para relacionar tarefas com seus modelos, adicione ao arquivo de configuração:

```bash
'morphable_types' => [
    \App\Models\Lead::class => 'Leads',
],
```
## Contribuição
Contribuições são bem-vindas! Sinta-se à vontade para abrir uma issue ou enviar um pull request.
## Licença
Este projeto está licenciado sob a licença MIT.
## Autor
Alessandro Nuunes de Oliveira