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

use Sonata\AdminBundle\Admin\Pool;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * This role provider compute the roles associated to the sonata admin classes.
 */
class SonataRoleProvider implements RoleProviderInterface
{

    protected $pool;

    protected $translator;

    public function __construct(Pool $pool, TranslatorInterface $translator)
    {
        $this->pool = $pool;
        $this->translator = $translator;
    }

    public function getRolesByGroups() :array
    {
        $rolesByGroup = [];

        foreach($this->pool->getAdminGroups() as $groupKey => $group){
            $rolesByGroup[] = [
                'groupLabel' => $this->translator->trans($group['label'], [], $group['label_catalogue']),
                'modules' => $this->getModulesByGroup($groupKey)
            ];
        }

        return $rolesByGroup;
    }

    public function getRoles(): array
    {
        $roles = [];

        foreach($this->pool->getAdminServiceIds() as $id) {
            try {
                $admin = $this->pool->getInstance($id);
            } catch (\Exception $e) {
                continue;
            }

            $baseRole = $admin->getSecurityHandler()->getBaseRole($admin);
            foreach (array_keys($admin->getSecurityInformation()) as $role) {
                $fullRole = sprintf($baseRole, $role);
                $roles[$fullRole] = $fullRole;
            }
        }

        return $roles;
    }


    protected function getModulesByGroup(string $group) :array
    {
        $modulesByGroup = [];

        foreach($this->pool->getAdminsByGroup($group) as $admin) {

            // if the user doesn't have MASTER role he's not allowed to view it
            if(!$admin->isGranted('MASTER')){
                continue;
            }

            $id = str_replace('.', '-', $admin->getCode());
            $modulesByGroup[$id] = [
                'label' => $this->translator->trans($admin->getLabel(), [], $admin->getTranslationDomain()),
                'id'    => $id,
                'permissions' => []
            ];

            $baseRole = $admin->getSecurityHandler()->getBaseRole($admin);
            foreach (array_keys($admin->getSecurityInformation()) as $role) {
                $fullRole = sprintf($baseRole, $role);
                $modulesByGroup[$id]['permissions'][$this->translator->trans($role, [], $admin->getTranslationDomain())] = $fullRole;
            }
        }

        return $modulesByGroup;
    }
}
