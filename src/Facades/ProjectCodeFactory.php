<?php


namespace Qylinfly\Permission\Facades;

use Illuminate\Support\Facades\Facade;

class ProjectCodeFactory extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'role_permission.project_type';
    }
}
