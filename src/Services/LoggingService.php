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
        // Retorna imediatamente se logging não estiver habilitado
        if (!config('tasks-management.logging.enabled', false)) {
            Log::debug('LoggingService::log - Logging desativado nas configurações');
            return;
        }

        $driver = config('tasks-management.logging.driver');
        $logModel = config('tasks-management.models.activity_log');

        // Se não houver driver ou modelo de log configurado, não faz nada
        if (!$driver || !$logModel) {
            Log::warning('Driver de log ou modelo de log não configurados');
            return;
        }

        try {
            // Registra o log com base no driver configurado
            
            switch ($driver) {
                case 'laravel-auditing':
                    self::logWithLaravelAuditing($model, $event, $attributes, $old);
                    break;
                case 'spatie-activitylog':
                    self::logWithSpatieActivityLog($model, $event, $attributes, $old);
                    break;
                default:
                    Log::warning("Driver de log desconhecido: {$driver}");
            }
            
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
        
        if (!$hasAuditMethod) {
            Log::warning('O modelo não possui o método audit()', [
                'model' => get_class($model),
            ]);
            return;
        }
        
        $model->auditEvent = $event;
        $model->isCustomEvent = true;
        $model->auditCustomOld = $old;
        $model->auditCustomNew = $attributes;
        
        // Registra a auditoria
        try {
            $model->audit();

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

        
        if (!$activityFunctionExists) {
            Log::warning('A função activity() não existe. O pacote spatie/laravel-activitylog está instalado?');
            return;
        }

        try {
            activity()
                ->performedOn($model)
                ->withProperties([
                    'attributes' => $attributes,
                    'old' => $old,
                ])
                ->log($event);
            
        } catch (\Exception $e) {
            Log::error('Erro ao registrar atividade com Spatie', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            throw $e;
        }
    }
}