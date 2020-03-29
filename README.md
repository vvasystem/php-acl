## php-acl

[![MIT License](http://img.shields.io/badge/license-MIT-9370d8.svg?style=flat)](http://opensource.org/licenses/MIT)

ACLs (Access Control Lists) is a lightweight acl manager for PHP

### Install

```
$ composer require vvasystem/php-acl
```
### Examples

#### Access to controller actions
```php
use PhpAcl\AccessRegister;
use PhpAcl\Helper;

$accessRegister = AccessRegister::getInstance();
$accessRegister->addResourceAccess(AccessRegister::RES_USER_PAGES, [
    AccessRegister::A_READ   => [AccessRegister::R_USER],
    AccessRegister::A_CREATE => [AccessRegister::R_SA],
    AccessRegister::A_UPDATE => [AccessRegister::R_SA],
    AccessRegister::A_DELETE => [AccessRegister::R_SA],
]);

$accessRegister->addActionAccess('index', 'details', [
    AccessRegister::RES_USER_PAGES => AccessRegister::A_READ,
]);

//or getting from session user
$rights = [AccessRegister::R_USER];

//and check access
if (Helper::hasActionAccess('index', 'details', $rights)) {
    //...
}
```

#### Access to resource

```php
use PhpAcl\AccessRegister;
use PhpAcl\Guard;
use PhpAcl\Helper;
use PhpAcl\UserInterface;

//add some constants
const TEST_RIGHT_1001    = 1001;
const TEST_RIGHT_100101  = 100101;

//implementation User interface
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

//next...
AccessRegister::getInstance()->addResourceAccess(AccessRegister::RES_USER_PAGES, [
    AccessRegister::A_READ   => [TEST_RIGHT_100101],
    AccessRegister::A_CREATE => [TEST_RIGHT_1001],
    AccessRegister::A_UPDATE => [TEST_RIGHT_1001],
    AccessRegister::A_DELETE => [TEST_RIGHT_1001],
]);

$user = new TestUser();
$user->setRights([AccessRegister::R_SA]);

$acl = Guard::getInstance();
if ($acl->CanDelete(AccessRegister::RES_USER_PAGES, null, $user)) {
    //...
}
```

#### Add own guards

```php
use PhpAcl\AccessRegister;
use PhpAcl\Guard;
use PhpAcl\ResourceGuardInterface;
use PhpAcl\ResourceInterface;
use PhpAcl\UserInterface;

//implementation ResourceGuard interface
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

//use own guard or extends DefaultResourceGuard
$acl = Guard::getInstance();

$acl->addResourceGuard(AccessRegister::RES_USER_PAGES, new TestResourceGuard());

$user = new TestUser();
$user->setRights([AccessRegister::R_USER]);

if ($acl->canUpdate(AccessRegister::RES_USER_PAGES, null, $user)) {
    //...
}
```