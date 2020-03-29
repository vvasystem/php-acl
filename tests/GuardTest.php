<?php

namespace PhpAclTest;

use PhpAcl\AccessRegister;
use PhpAcl\DefaultResourceGuard;
use PhpAcl\Guard;
use PhpAcl\Helper;
use PhpAcl\ResourceGuardInterface;
use PhpAcl\ResourceInterface;
use PhpAcl\UserInterface;
use PHPUnit\Framework\TestCase;

class GuardTest extends TestCase
{

    const TEST_RES_1 = 3;
    const TEST_RES_2 = 4;

    const TEST_RIGHT_1001    = 1001;
    const TEST_RIGHT_100101  = 100101;
    const TEST_RIGHT_1001011 = 1001011;

    public function testGetResourceGuard()
    {
        $acl = Guard::getInstance();

        $resources = $acl->getResourceGuards(self::TEST_RES_1);
        $this->assertCount(1, $resources);
        $this->assertInstanceOf(DefaultResourceGuard::class, $resources[0]);

        $acl->addResourceGuard(self::TEST_RES_2, new TestResourceGuard());

        $resources = $acl->getResourceGuards(self::TEST_RES_2);
        $this->assertCount(1, $resources);
        $this->assertInstanceOf(TestResourceGuard::class, $resources[0]);
    }

    public function testCanRead()
    {
        $accessRegister = AccessRegister::getInstance();

        $accessRegister->addResourceAccess(AccessRegister::RES_GUEST_PAGES, [
            AccessRegister::A_READ => [AccessRegister::R_GUEST],
        ]);
        $accessRegister->addResourceAccess(AccessRegister::RES_USER_PAGES, [
            AccessRegister::A_READ => [AccessRegister::R_USER],
        ]);

        $user = new TestUser();
        $user->setRights([AccessRegister::R_GUEST]);

        $acl = Guard::getInstance();

        $this->assertTrue($acl->canRead(AccessRegister::RES_GUEST_PAGES, null, $user));

        $user->setRights([]);
        $this->assertFalse($acl->canRead(AccessRegister::RES_USER_PAGES, null, $user));
    }

    public function testCanCreate()
    {
        $accessRegister = AccessRegister::getInstance();

        $accessRegister->addResourceAccess(AccessRegister::RES_GUEST_PAGES, [
            AccessRegister::A_CREATE => [AccessRegister::R_USER],
        ]);
        $accessRegister->addResourceAccess(AccessRegister::RES_USER_PAGES, [
            AccessRegister::A_CREATE => [AccessRegister::R_SA],
        ]);

        $user = new TestUser();
        $user->setRights([AccessRegister::R_SA]);

        $acl = Guard::getInstance();

        $this->assertTrue($acl->canCreate(AccessRegister::RES_GUEST_PAGES, null, $user));

        $user->setRights([AccessRegister::R_USER]);
        $this->assertFalse($acl->canCreate(AccessRegister::RES_USER_PAGES, null, $user));
    }

    public function testCanUpdate()
    {
        $accessRegister = AccessRegister::getInstance();

        $accessRegister->addResourceAccess(AccessRegister::RES_GUEST_PAGES, [
            AccessRegister::A_UPDATE => [self::TEST_RIGHT_100101],
        ]);
        $accessRegister->addResourceAccess(AccessRegister::RES_USER_PAGES, [
            AccessRegister::A_UPDATE => [self::TEST_RIGHT_1001],
        ]);

        $user = new TestUser();
        $user->setRights([self::TEST_RIGHT_1001]);

        $acl = Guard::getInstance();

        $this->assertTrue($acl->canUpdate(AccessRegister::RES_GUEST_PAGES, null, $user));

        $user->setRights([self::TEST_RIGHT_100101]);
        $this->assertFalse($acl->canUpdate(AccessRegister::RES_USER_PAGES, null, $user));
    }

    public function testCanDelete()
    {
        $accessRegister = AccessRegister::getInstance();

        $accessRegister->addResourceAccess(AccessRegister::RES_GUEST_PAGES, [
            AccessRegister::A_DELETE => [AccessRegister::R_SA],
        ]);
        $accessRegister->addResourceAccess(AccessRegister::RES_USER_PAGES, [
            AccessRegister::A_DELETE => [self::TEST_RIGHT_1001011],
        ]);

        $user = new TestUser();
        $user->setRights([AccessRegister::R_SA]);

        $acl = Guard::getInstance();

        $this->assertTrue($acl->canDelete(AccessRegister::RES_GUEST_PAGES, null, $user));

        $user->setRights([AccessRegister::R_USER]);
        $this->assertFalse($acl->canDelete(AccessRegister::RES_USER_PAGES, null, $user));
    }

}

class TestUser implements UserInterface
{
    /** @var array */
    private $rights = [];

    public function setRights(array $rights)
    {
        $this->rights = $rights;
    }

    /**
     * @return int[]
     */
    public function getRights(): array
    {
        return $this->rights;
    }

    public function hasResourceAccess(int $resourceType, int $accessType): bool
    {
        return Helper::hasResourceAccess($resourceType, $accessType, $this->getRights());
    }
}

class TestResourceGuard implements ResourceGuardInterface
{

    public function checkResourceTypeAccess(int $accessType, int $resourceType, ?ResourceInterface $context, UserInterface $user): bool
    {
        // some logic
        return true;
    }

    public function checkResourceAccess(int $accessType, ResourceInterface $resource, ?ResourceInterface $context, UserInterface $user): bool
    {
        // some logic
        return true;
    }

}
