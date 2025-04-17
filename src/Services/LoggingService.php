<?php

namespace Alessandronuunes\TasksManagement\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class LoggingService
{
    /**
     * Registra uma atividade para o modelo
     */
    public static function log(Model $model, string $event, array $attributes = [], array $old = []): void
    {
        // Verifica se o logging está ativado
        Log::debug('LoggingService::log - Iniciando', [
            'model' => get_class($model),
            'id' => $model->id,
            'event' => $event,
            'config_enabled' => config('tasks-management.logging.enabled', false),
        ]);
        
        if (!config('tasks-management.logging.enabled', false)) {
            Log::debug('LoggingService::log - Logging desativado nas configurações');
            return;
        }

        $driver = config('tasks-management.logging.driver');
        $logModel = config('tasks-management.models.activity_log');

        Log::debug('LoggingService::log - Configurações', [
            'driver' => $driver,
            'logModel' => $logModel,
        ]);

        // Se não houver driver ou modelo de log configurado, não faz nada
        if (!$driver || !$logModel) {
            Log::debug('LoggingService::log - Driver ou modelo de log não configurado');
            return;
        }

        try {
            // Registra o log com base no driver configurado
            Log::debug('LoggingService::log - Tentando registrar com driver: ' . $driver);
            
            switch ($driver) {
                case 'laravel-auditing':
                    Log::debug('LoggingService::log - Usando Laravel Auditing');
                    self::logWithLaravelAuditing($model, $event, $attributes, $old);
                    break;
                case 'spatie-activitylog':
                    Log::debug('LoggingService::log - Usando Spatie Activity Log');
                    self::logWithSpatieActivityLog($model, $event, $attributes, $old);
                    break;
                default:
                    Log::warning("Driver de log desconhecido: {$driver}");
            }
            
            Log::debug('LoggingService::log - Log registrado com sucesso');
        } catch (\Exception $e) {
            Log::error("Erro ao registrar log: {$e->getMessage()}", [
                'model' => get_class($model),
                'id' => $model->id,
                'event' => $event,
                'trace' => $e->getTraceAsString(),
            ]);
        }
    }

    /**
     * Registra log usando Laravel Auditing
     */
    private static function logWithLaravelAuditing(Model $model, string $event, array $attributes, array $old): void
    {
        // Verifica se o modelo implementa a interface Auditable
        $hasAuditMethod = method_exists($model, 'audit');
        Log::debug('logWithLaravelAuditing - Verificação do método audit', [
            'hasAuditMethod' => $hasAuditMethod,
            'model' => get_class($model),
        ]);
        
        if (!$hasAuditMethod) {
            Log::warning('O modelo não possui o método audit()', [
                'model' => get_class($model),
            ]);
            return;
        }

        // Configura o evento de auditoria
        Log::debug('logWithLaravelAuditing - Configurando evento de auditoria', [
            'event' => $event,
            'attributes_count' => count($attributes),
            'old_count' => count($old),
        ]);
        
        $model->auditEvent = $event;
        $model->isCustomEvent = true;
        $model->auditCustomOld = $old;
        $model->auditCustomNew = $attributes;
        
        // Registra a auditoria
        Log::debug('logWithLaravelAuditing - Chamando método audit()');
        try {
            $result = $model->audit();
            Log::debug('logWithLaravelAuditing - Audit concluído', [
                'result' => $result,
            ]);
        } catch (\Exception $e) {
            Log::error('Erro ao chamar audit()', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            throw $e;
        }
    }

    /**
     * Registra log usando Spatie Activity Log
     */
    private static function logWithSpatieActivityLog(Model $model, string $event, array $attributes, array $old): void
    {
        // Verifica se a função activity() existe
        $activityFunctionExists = function_exists('activity');
        Log::debug('logWithSpatieActivityLog - Verificação da função activity()', [
            'activityFunctionExists' => $activityFunctionExists,
        ]);
        
        if (!$activityFunctionExists) {
            Log::warning('A função activity() não existe. O pacote spatie/laravel-activitylog está instalado?');
            return;
        }

        // Registra a atividade
        Log::debug('logWithSpatieActivityLog - Registrando atividade');
        try {
            activity()
                ->performedOn($model)
                ->withProperties([
                    'attributes' => $attributes,
                    'old' => $old,
                ])
                ->log($event);
            
            Log::debug('logWithSpatieActivityLog - Atividade registrada com sucesso');
        } catch (\Exception $e) {
            Log::error('Erro ao registrar atividade com Spatie', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            throw $e;
        }
    }
}