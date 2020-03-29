<?php

namespace PhpAclTest;

use PhpAcl\AccessRegister;
use PhpAcl\Helper;
use PHPUnit\Framework\TestCase;

class HelperTest extends TestCase
{

    const TEST_RIGHT_5001    = 5001;
    const TEST_RIGHT_500101  = 500101;
    const TEST_RIGHT_5001011 = 5001011;

    public function testHasActionAccess()
    {
        $accessRegister = AccessRegister::getInstance();

        $accessRegister->addResourceAccess(AccessRegister::RES_USER_PAGES, [
            AccessRegister::A_READ => [AccessRegister::R_USER],
        ]);
        $accessRegister->addActionAccess('index', 'details', [
            AccessRegister::RES_USER_PAGES => AccessRegister::A_READ,
        ]);

        $this->assertTrue(Helper::hasActionAccess('index',  'details', [AccessRegister::R_USER]));
        $this->assertFalse(Helper::hasActionAccess('index', 'details', [AccessRegister::R_GUEST]));
        $this->assertFalse(Helper::hasActionAccess('index', 'delete', [AccessRegister::R_USER]));
    }

    public function testHasResourceAccess()
    {
        $accessRegister = AccessRegister::getInstance();

        $accessRegister->addResourceAccess(AccessRegister::RES_USER_PAGES, [
            AccessRegister::A_READ => [AccessRegister::R_USER],
        ]);

        $this->assertTrue(Helper::hasResourceAccess(AccessRegister::RES_USER_PAGES, AccessRegister::A_READ, [AccessRegister::R_USER]));
        $this->assertFalse(Helper::hasResourceAccess(AccessRegister::RES_USER_PAGES, AccessRegister::A_UPDATE, [AccessRegister::R_USER]));
    }

    public function testHasRight()
    {
        $this->assertTrue(Helper::hasRight([AccessRegister::R_USER], [AccessRegister::R_SA]));
        $this->assertFalse(Helper::hasRight([AccessRegister::R_USER], [AccessRegister::R_GUEST]));
        $this->assertTrue(Helper::hasRight([self::TEST_RIGHT_500101], [self::TEST_RIGHT_5001]));
        $this->assertFalse(Helper::hasRight([self::TEST_RIGHT_5001], [self::TEST_RIGHT_5001011]));
        $this->assertFalse(Helper::hasRight([self::TEST_RIGHT_500101], [AccessRegister::R_USER]));
    }

}