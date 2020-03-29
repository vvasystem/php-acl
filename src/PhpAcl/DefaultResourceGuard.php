<?php

namespace PhpAcl;

class DefaultResourceGuard implements ResourceGuardInterface
{

    public function checkResourceTypeAccess(int $accessType, int $resourceType, ?ResourceInterface $context, UserInterface $user): bool
    {
        switch ($accessType) {
            case AccessRegister::A_READ:
                return $this->canRead($resourceType, $context, $user);
	        case AccessRegister::A_CREATE:
                return $this->canCreate($resourceType, $context, $user);
	        case AccessRegister::A_UPDATE:
                return $this->canUpdate($resourceType, $context, $user);
	        case AccessRegister::A_DELETE:
                return $this->canDelete($resourceType, $context, $user);
	    }
        return false;
    }

    public function checkResourceAccess(int $accessType, ResourceInterface $resource, ?ResourceInterface $context, UserInterface $user): bool
    {
        switch ($accessType) {
            case AccessRegister::A_READ:
                return $this->checkRead($resource, $context, $user);
            case AccessRegister::A_CREATE:
                return $this->checkCreate($resource, $context, $user);
            case AccessRegister::A_UPDATE:
                return $this->checkUpdate($resource, $context, $user);
            case AccessRegister::A_DELETE:
                return $this->checkDelete($resource, $context, $user);
        }
        return false;
    }

    public function canRead(int $resourceType, ?ResourceInterface $context, UserInterface $user): bool
    {
        return $user->hasResourceAccess($resourceType, AccessRegister::A_READ);
    }

    public function canCreate(int $resourceType, ?ResourceInterface $context, UserInterface $user): bool
    {
        return $user->hasResourceAccess($resourceType, AccessRegister::A_CREATE);
    }

    public function canUpdate(int $resourceType, ?ResourceInterface $context, UserInterface $user): bool
    {
        return $user->hasResourceAccess($resourceType, AccessRegister::A_UPDATE);
    }

    public function canDelete(int $resourceType, ?ResourceInterface $context, UserInterface $user): bool
    {
        return $user->hasResourceAccess($resourceType, AccessRegister::A_DELETE);
    }

    public function checkRead(ResourceInterface $resource, ResourceInterface $context, UserInterface $user): bool
    {
        return $this->canRead($resource->getResourceType(), $context, $user);
    }

    public function checkCreate(ResourceInterface $resource, ResourceInterface $context, UserInterface $user): bool
    {
        return $this->canCreate($resource->getResourceType(), $context, $user);
    }

    public function checkUpdate(ResourceInterface $resource, ResourceInterface $context, UserInterface $user): bool
    {
        return $this->canUpdate($resource->getResourceType(), $context, $user);
    }

    public function checkDelete(ResourceInterface $resource, ResourceInterface $context, UserInterface $user): bool
    {
        return $this->canDelete($resource->getResourceType(), $context, $user);
    }

}