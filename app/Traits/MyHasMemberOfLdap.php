<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 2/9/19
 * Time: 12:46 PM
 */

namespace App\Traits;


use Adldap\Models\Concerns\HasMemberOf;

class MyHasMemberOfLdap
{
    use HasMemberOf {
        HasMemberOf::inGroup as parentInGroup;
    }

    public function inGroup($group, $recursive = true)
    {
//         = $this->getGroups(['cn'], $recursive);
        $memberOf=Adldap::search()->where('cn', '=', 'enabled')->get()->first();
        if ($group instanceof Collection) {
            // If we've been given a collection then we'll convert
            // it to an array to normalize the value.
            $group = $group->toArray();
        }

        $groups = is_array($group) ? $group : [$group];

        foreach ($groups as $group) {
            // We need to iterate through each given group that the
            // model must be apart of, then go through the models
            // actual groups and perform validation.
            $exists = $memberOf->filter(function (Group $parent) use ($group) {
                    return $this->groupIsParent($group, $parent);
                })->count() !== 0;

            if (!$exists) {
                // If the current group isn't at all contained
                // in the memberOf collection, we'll
                // return false here.
                return false;
            }
        }

        return true;
    }

}