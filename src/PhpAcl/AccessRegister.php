<?php

namespace PhpAcl;

class AccessRegister
{

    use SingletonTrait;

    const R_SA    = 0;
    const R_USER  = 9;
    const R_GUEST = 99;

    const RES_GUEST_PAGES = 1;
    const RES_USER_PAGES  = 2;

    const A_CREATE = 1;
    const A_READ   = 2;
    const A_UPDATE = 4;
    const A_DELETE = 8;

    /** @var array */
    private $actionAccess = [];

    /** @var array */
    private $resourceAccess = [];

    public function addActionAccess(string $controllerName, string $actionName, array $actionAccess)
    {
        $this->actionAccess[$controllerName] = [$actionName => $actionAccess];
    }

    public function addResourceAccess(int $resourceType, array $resourceAccess)
    {
        $this->resourceAccess[$resourceType] = $resourceAccess;
    }

    public function getActionAccesses(): array
    {
        return $this->actionAccess;
    }

    public function getResourceAccesses(): array
    {
        return $this->resourceAccess;
    }

}