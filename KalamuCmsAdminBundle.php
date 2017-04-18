<?php

namespace Kalamu\CmsAdminBundle;

use Kalamu\CmsAdminBundle\DependencyInjection\Compiler\CmsCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class KalamuCmsAdminBundle extends Bundle
{

    public function build(ContainerBuilder $container) {
        parent::build($container);

        $container->addCompilerPass(new CmsCompilerPass());
    }

}
