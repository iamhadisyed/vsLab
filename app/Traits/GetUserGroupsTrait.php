<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 10/18/18
 * Time: 1:36 PM
 */

namespace App\Traits;

use Auth;
use App\Group, App\Site;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;

trait GetUserGroupsTrait
{

    public function userGroups()
    {
        $user = Auth::user();

        $grps = $user->usersGroupsRoles()->whereHas('Role', function($query) {
            $query->whereIn('name', ['student', 'group_user', 'instructor', 'TA', 'group_owner']);
        })->groupBy('group_id')->get();

        $groups = new collection();
//        $g_ids = array();
        foreach($grps as $group) {
            $g = Group::find($group->group_id);
            if (is_null($g)) {
                continue;
            }


            $site_name = Site::find($g->getAttribute('site_id'))->getAttribute('name');
            $groups->push( (object) ['id' => $group->group_id, 'name' => $g->getAttribute('name'), 'site' => $site_name ]);
//            array_push($g_ids, $g->getAttribute('id'));
        }

        return $groups;
    }
}

