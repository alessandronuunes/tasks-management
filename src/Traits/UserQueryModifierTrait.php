<?php

namespace Alessandronuunes\TasksManagement\Traits;

trait UserQueryModifierTrait
{
    protected static function applyUserQueryModifier($query)
    {
        $userQueryModifier = app('tasks-management.user-query-modifier');
        
        if ($userQueryModifier && is_callable($userQueryModifier)) {
            return call_user_func($userQueryModifier, $query);
        }
        
        return $query;
    }
}