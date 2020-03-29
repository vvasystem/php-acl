<?php

namespace PhpAcl;

class Guard
{

    use SingletonTrait;

    /** @var array */
    private $resourceGuard = [];

    public function addResourceGuard(int $resourceType, ResourceGuardInterface $resourceGuard)
    {
        $this->resourceGuard[$resourceType][] = $resourceGuard;
    }

    public function canRead(int $resourceType, ?ResourceInterface $context, UserInterface $user): bool
    {
        return $this->checkResourceTypeAccess(AccessRegister::A_READ, $resourceType, $context, $user);
    }

    public function canCreate(int $resourceType, ?ResourceInterface $context, UserInterface $user): bool
    {
        return $this->checkResourceTypeAccess(AccessRegister::A_CREATE, $resourceType, $context, $user);
    }

    public function canUpdate(int $resourceType, ?ResourceInterface $context, UserInterface $user): bool
    {
        return $this->checkResourceTypeAccess(AccessRegister::A_UPDATE, $resourceType, $context, $user);
    }

    public function canDelete(int $resourceType, ?ResourceInterface $context, UserInterface $user): bool
    {
        return $this->checkResourceTypeAccess(AccessRegister::A_DELETE, $resourceType, $context, $user);
    }

    private function checkResourceTypeAccess(int $accessType, int $resourceType, ?ResourceInterface $context, UserInterface $user): bool
    {
        $resourceGuards = $this->getResourceGuards($resourceType);
        foreach ($resourceGuards as $resourceGuard) {
            if ($resourceGuard->checkResourceTypeAccess($accessType, $resourceType, $context, $user)) {
                return true;
            }
        }
        return false;
    }

    /**
     * @param int $resourceType
     * @return ResourceGuardInterface[]
     */
    public function getResourceGuards(int $resourceType): array
    {
        return $this->resourceGuard[$resourceType] ?? [new DefaultResourceGuard()];
    }

    public function checkRead(ResourceInterface $resource, ?ResourceInterface $context, UserInterface $user): bool
    {
        return $this->canRead($resource->getResourceType(), $context, $user);
    }

    public function checkCreate(ResourceInterface $resource, ?ResourceInterface $context, UserInterface $user): bool
    {
        return $this->canCreate($resource->getResourceType(), $context, $user);
    }

    public function checkUpdate(ResourceInterface $resource, ?ResourceInterface $context, UserInterface $user): bool
    {
        return $this->canUpdate($resource->getResourceType(), $context, $user);
    }

    public function checkDelete(ResourceInterface $resource, ?ResourceInterface $context, UserInterface $user): bool
    {
        return $this->canDelete($resource->getResourceType(), $context, $user);
    }

}