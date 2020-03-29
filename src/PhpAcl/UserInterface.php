<?php

namespace PhpAcl;

interface UserInterface
{

    public function hasResourceAccess(int $resourceType, int $accessType): bool;

}