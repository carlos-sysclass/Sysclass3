<?php
namespace Sysclass\Acl;

use Phalcon\Mvc\User\Component,
    Phalcon\Acl\Role as Role,
    Sysclass\Models\Acl\Role as AclRole,
    Sysclass\Models\Acl\Resource as AclResource,
    Sysclass\Models\Acl\RolesResources,
    Sysclass\Models\Users\User;
    
    /*,
    Phalcon\Events\EventsAwareInterface,
    Phalcon\Acl\AdapterInterface;
    */

class Adapter extends \Phalcon\Acl\Adapter\Memory
{
    // SINGLETON PATTERN
    private static $default = null;
    private static $user = null;
    public static function getDefault(User $user = null) {
        if (is_null(self::$default)) {
            self::$default = new self();
            self::$default->initialize($user);
        }

        return self::$default;
    }

    private function initialize(User $user = null) {
        $this->setDefaultAction(\Phalcon\Acl::DENY);

        $group = $user->getGroup();
        if ($group) {
            $groupRoles = $group->getRoles();
            foreach($groupRoles as $role) {
                $this->addPermission($role);
            }
        }
        
        $roles = $user->getUserRoles();
        // TODO: Include all roles from groups!!!!
        foreach($roles as $role) {
            $this->addPermission($role);
        }
    }

    private function addPermission(AclRole $role) {
        $this->addRole(
            new \Phalcon\Acl\Role($role->name, $role->description)
        );
        $resourcesRS = $role->getResources();

        $connectByField = "group";

        $resources = array();
        foreach($resourcesRS as $item) {
            $connectByValue = $item->{$connectByField};
            if (!array_key_exists($connectByValue, $resources)) {
                $resources[$connectByValue] = array();
            }
            $resources[$connectByValue][] = $item->toArray();
        }

        foreach($resources as $resource => $operations) {
            $operationsSingle = array_column($operations, "name");
            $this->addResource(
                new \Phalcon\Acl\Resource($resource),
                $operationsSingle
            );



            foreach($operationsSingle as $operation) {
                //echo sprintf("ALLOW: %s %s %s <br />", $role->name, $resource, $operation);
                $this->allow($role->name, $resource, $operation);
            }
        }
    }

    public function isUserAllowed(User $user = null, $resource, $operation) {
        
        $group = $user->getGroup();
        if ($group) {
            $groupRoles = $user->getGroup()->getRoles();
            foreach($groupRoles as $role) {
                //echo sprintf("CHECKING GROUP : %s %s %s<br />", $role->name, $resource, $operation);
                $status = $this->isAllowed($role->name, $resource, $operation);
                if ($status) {
                    return $status;
                }
            }
        }

        $roles = $user->getUserRoles();
        foreach($roles as $role) {
            //echo sprintf("CHECKING USER : %s %s %s<br />", $role->name, $resource, $operation);
            $status = $this->isAllowed($role->name, $resource, $operation);
            if ($status) {
                return $status;
            }
        }
        return false;

    }

    /*
    public function setEventsManager(\Phalcon\Events\ManagerInterface $eventsManager)
    {
        $this->_eventsManager = $eventsManager;
    }

    public function getEventsManager()
    {
        return $this->_eventsManager;
    }
    */
    /*
    public function setDefaultAction (unknown $defaultAccess) {
        //...
    }
    public function getDefaultAction() {
        //...
    }
    public function addRole (unknown $role, [unknown $accessInherits]) {
        //...
    }
    public function addInherit (unknown $roleName, unknown $roleToInherit) {
        //...
    }
    public function isRole (unknown $roleName) {
        //...
    }
    public function isResource (unknown $resourceName) {
        //...
    }
    public function addResource (unknown $resourceObject, unknown $accessList) {
        //...
    }
    public function addResourceAccess (unknown $resourceName, unknown $accessList) {
        //...
    }
    public function dropResourceAccess (unknown $resourceName, unknown $accessList) {
        //...
    }
    public function allow (unknown $roleName, unknown $resourceName, unknown $access) {
        //...
    }
    public function deny (unknown $roleName, unknown $resourceName, unknown $access) {
        //...
    }
    public function isAllowed (unknown $roleName, unknown $resourceName, unknown $access) {
        //...
    }
    public function getActiveRole () {
        //...
    }
    public function getActiveResource () {
        //...
    }
    public function getActiveAccess () {
        //...
    }
    public function getRoles () {
        //...
    }
    public function getResources () {
        //...   
    }
    */
        //$this->_eventsManager->fire("authentication:beforeLogout", $this);

}
