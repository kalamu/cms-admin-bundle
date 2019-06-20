<?php

/*
 * This file is part of the kalamu/cms-admin-bundle package.
 *
 * (c) ETIC Services
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Kalamu\CmsAdminBundle;

use Kalamu\CmsAdminBundle\DependencyInjection\Compiler\CmsCompilerPass;
use Kalamu\CmsAdminBundle\DependencyInjection\Compiler\RoleProviderCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class KalamuCmsAdminBundle extends Bundle
{

    public function build(ContainerBuilder $container) {
        parent::build($container);

        $container->addCompilerPass(new CmsCompilerPass());
        $container->addCompilerPass(new RoleProviderCompilerPass());
    }

}
