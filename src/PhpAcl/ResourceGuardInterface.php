<?php

namespace PhpAcl;

interface ResourceGuardInterface
{

    public function checkResourceTypeAccess(int $accessType, int $resourceType, ?ResourceInterface $context, UserInterface $user): bool;

    public function checkResourceAccess(int $accessType, ResourceInterface $resource, ?ResourceInterface $context, UserInterface $user): bool;

}