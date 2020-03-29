<?php

namespace PhpAcl;

class Helper
{

    public static function hasActionAccess(string $controller, string $action, array $rights)
    {
        if (empty($rights)) {
            return false;
        }
        if (static::isSA($rights)) {
            return true;
        }

        $actionList = AccessRegister::getInstance()->getActionAccesses();
        if (empty($actionList[$controller]) || empty($actionList[$controller][$action])) {
            return false;
        }
        $access = $actionList[$controller][$action];
        foreach ($access as $resourceType => $accessType) {
            if (static::hasResourceAccess($resourceType, $accessType, $rights)) {
                return true;
            }
        }
        return false;
    }

    public static function hasResourceAccess(int $resourceType, int $accessType, array $rights): bool
    {
        $resourceTypes = AccessRegister::getInstance()->getResourceAccesses();
        if (empty($resourceTypes[$resourceType]) || empty($resourceTypes[$resourceType][$accessType])) {
            return false;
        }

	    return static::hasRight($resourceTypes[$resourceType][$accessType], $rights);
    }

    /**
     * @param int[] $checkRights
     * @param int[] $rights
     * @return bool
     */
    public static function hasRight(array $checkRights, array $rights): bool
    {
        if (static::isSA($rights)) {
            return true;
        }

        $rightsLeft = [];
        $rightsRight = [];
        foreach ($rights as $n => $right) {
            $rightsLeft[$n] = static::padding($right);
            $rightsRight[$n] = static::padding($right + 1) - 1;
        }
        foreach ($checkRights as $accessRight) {
            $kAccessRight = static::padding($accessRight);
            foreach ($rights as $n => $right) {
                if ($kAccessRight >= $rightsLeft[$n] && $kAccessRight <= $rightsRight[$n]) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * @param int[] $rights
     * @return bool
     */
    private static function isSA(array $rights): bool
    {
        return \in_array(AccessRegister::R_SA, $rights, true);
    }

    private static function padding(int $c)
    {
        while ($c && $c <= 100000000) {
            $c *= 10;
        }
        return $c;
    }

}