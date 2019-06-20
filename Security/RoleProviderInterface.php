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

interface RoleProviderInterface
{

    /**
     * Get the list of roles organised by groups
     */
    public function getRolesByGroups() :array;

    /**
     * Get the list of roles as a flat array
     */
    public function getRoles() :array;

}