<?php

/*
 * This file is part of the kalamu/cms-admin-bundle package.
 *
 * (c) ETIC Services
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Kalamu\CmsAdminBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Kalamu\CmsAdminBundle\Entity\ContextPublication;



class ImportContextCommand extends ContainerAwareCommand {

    protected function configure() {
        $this
                ->setDefinition(array( ))
                ->setDescription("Create publication contexts in the database")
                ->setHelp(<<<EOT
The <info>kalamu:context:import</info> command import in the database the
publication contexts that have been configured in the CMS.

<info>php app/console kalamu:context:import</info>
EOT
                )
                ->setName('kalamu:context:import')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $contexts = $this->getContainer()->getParameter('kalamu_cms_core.contexts');
        $em = $this->getContainer()->get('doctrine')->getManager();

        foreach($contexts as $name => $config){
            $context = $em->getRepository('KalamuCmsAdminBundle:ContextPublication')->findOneBy(array('name' => $name));
            if(!$context){
                $context = new ContextPublication();
                $context->setName($name);
            }
            $context->setTitle($config['title']);
            $em->persist($context);
        }
        $em->flush();
    }

}
