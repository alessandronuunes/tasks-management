<?php

namespace Alessandronuunes\TasksManagement\Traits;

trait AuthorizedUsersTrait
{
    
    protected static function isAuthorized(): bool
    {
        $authorizedUsers = app('tasks-management.authorized-users', []);
        
        if (empty($authorizedUsers)) {
            return true;
        }
        
        $user = auth()->user();
        
        if (!$user) {
            return false;
        }
        
        foreach ($authorizedUsers as $authorizedUser) {
            if (
                (is_numeric($authorizedUser) && $user->id == $authorizedUser) ||
                (is_string($authorizedUser) && $user->email == $authorizedUser)
            ) {
                return true;
            }
        }
        
        return false;
    }
}