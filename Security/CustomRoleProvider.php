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
 * Role provider for static roles
 */
class CustomRoleProvider implements RoleProviderInterface
{

    protected $rolesByGroups = [
        [
            'groupLabel' => 'admin',
            'modules' => [
                'divers' => [
                    'label' => 'Divers',
                    'id'    => 'divers',
                    'permissions' => [
                        'Super administrator' => 'ROLE_SUPER_ADMIN',
                        'Access to the CMS configuration' => 'ROLE_ADMIN_CONFIGURATION'
                    ]
                ]
            ]
        ]];

    public function getRolesByGroups() :array
    {
        return $this->rolesByGroups;
    }

    public function getRoles(): array
    {
        $roles = [];

        foreach($this->rolesByGroups as $group){
            foreach($group['modules'] as $module){
                foreach($module['permissions'] as $label => $role){
                    $roles[$role] = $role;
                }
            }
        }

        return $roles;
    }

}
