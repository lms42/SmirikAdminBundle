<?php

namespace Smirik\AdminBundle\Model;

use FOS\UserBundle\Propel\UserQuery as BaseUserQuery;

class UserQuery extends BaseUserQuery
{

    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof UserQuery) {
            return $criteria;
        }
        $query = new UserQuery();
        if (null !== $modelAlias) {
            $query->setModelAlias($modelAlias);
        }
        if ($criteria instanceof Criteria) {
            $query->mergeWith($criteria);
        }

        return $query;
    }

    
    public function orderByGroups($order)
    {
        return $this
            ->useUserGroupQuery()
                ->useGroupQuery()
                    ->orderByName($order)
                ->endUse()
            ->endUse()
        ;
    }
    
    public function filterByGroups($groups, $comparison = null)
    {
        return $this
            ->useUserGroupQuery()
                ->useGroupQuery()
                    ->filterByName($groups.'%', $comparison)
                ->endUse()
            ->endUse()
        ;
    }
    
    public function orderByGetname($order)
    {
        return $this
            ->useProfileQuery()
                ->orderByLastName($order)
                ->orderByFirstName($order)
            ->endUse()
        ;
    }

    public function filterByGetname($name, $comparison = null)
    {
        return $this
            ->useProfileQuery()
                ->filterByLastName($name, $comparison)
                ->_or()
                ->filterByFirstName($name, $comparison)
            ->endUse()
        ;
    }
    
}