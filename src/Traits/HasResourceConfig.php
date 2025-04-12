<?php

declare(strict_types=1);

namespace Alessandronuunes\TasksManagement\Traits;

trait HasResourceConfig
{
    public static function getNavigationIcon(): ?string
    {
        $resourceKey = static::getResourceKey();
        return config("tasks-management.resources.{$resourceKey}.navigation.icon");
    }

    public static function getNavigationSort(): ?int
    {
        $resourceKey = static::getResourceKey();
        return config("tasks-management.resources.{$resourceKey}.navigation.sort");
    }

    public static function getNavigationGroup(): ?string
    {
        $resourceKey = static::getResourceKey();
        return config("tasks-management.resources.{$resourceKey}.navigation.group");
    }

    

    public static function getSlug(): string
    {
        $resourceKey = static::getResourceKey();
        return config("tasks-management.resources.{$resourceKey}.navigation.slug") ?? static::getDefaultSlug();
    }

    protected static function getDefaultSlug(): string
    {
        return str_replace('_', '-', static::getResourceKey());
    }

    protected static function getResourceKey(): string
    {
        return strtolower(str_replace('Resource', '', class_basename(static::class)));
    }
}