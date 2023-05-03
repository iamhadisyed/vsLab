<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Permission extends \Spatie\Permission\Models\Permission
{
    public static function defaultPermissions()
    {
        return [
            'users_view', 'users_add', 'users_edit', 'users_delete',
            'roles_view', 'roles_add', 'roles_edit', 'roles_delete',
            'permissions_view', 'permissions_add', 'permissions_edit', 'permissions_delete',
            'groups_view', 'groups_add', 'groups_edit', 'groups_delete',
            'subgroups_view', 'subgroups_add', 'subgroups_edit', 'subgroups_delete',
            'project_view', 'project_add', 'project_edit', 'project_delete',
            'labs-design_view', 'labs-design_add', 'labs-design_edit', 'labs-design_delete',
            'labs-deploy_view', 'labs-deploy_add', 'labs-deploy_edit', 'labs-deploy_delete',
            'resources_view', 'resources_add', 'resources_edit', 'resources_delete',

        ];
    }
}
