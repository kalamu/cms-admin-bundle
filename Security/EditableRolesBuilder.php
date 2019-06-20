<?php

/*
 * This file is part of the kalamu/cms-admin-bundle package.
 *
 * (c) ETIC Services
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Kalamu\CmsAdminBundle\Security;

/**
 * This service fetch every roles that come from various provider.
 * Then it compile then to build the SecurityRolesType form type.
 */
class EditableRolesBuilder
{
    protected $roleProviders;

    public function __construct()
    {
        $this->roleProviders = [];
    }

    public function addRoleProvider(RoleProviderInterface $roleProvider)
    {
        $this->roleProviders[] = $roleProvider;
    }

    /**
     * Get the list of roles organised by groups
     * @return array
     */
    public function getRolesByGroups()
    {
        $rolesByGroup = [];
        foreach($this->roleProviders as $roleProvider){
            foreach($roleProvider->getRolesByGroups() as $group){

                if(!array_key_exists($group['groupLabel'], $rolesByGroup)){
                    $rolesByGroup[$group['groupLabel']] = $group;
                }else{
                    $rolesByGroup[$group['groupLabel']]['modules'] = array_merge_recursive($rolesByGroup[$group['groupLabel']]['modules'], $group['modules']);
                }
            }
        }
        return $rolesByGroup;
    }

    public function getRoles()
    {
        $roles = [];
        foreach($this->roleProviders as $roleProvider){
            $roles = array_merge_recursive($roles, $roleProvider->getRoles());
        }
        return $roles;
    }
}