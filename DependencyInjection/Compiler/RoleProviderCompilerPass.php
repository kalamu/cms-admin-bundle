<?php

/*
 * This file is part of the kalamu/cms-admin-bundle package.
 *
 * (c) ETIC Services
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Kalamu\CmsAdminBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;

/**
 * CompilerPass to register the role provides
 */
class RoleProviderCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if($container->hasDefinition('kalamu.security.editable_roles_builder')){
            $roleBuilder = $container->findDefinition('kalamu.security.editable_roles_builder');
            $services = $container->findTaggedServiceIds('security.role_provider');
            foreach(array_keys($services) as $serviceId){
                $roleBuilder->addMethodCall('addRoleProvider', [new Reference($serviceId)]);
            }
        }
    }
}