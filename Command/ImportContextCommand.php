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

    /**
     * Configuration de la commande
     */
    protected function configure() {
        $this
                ->setDefinition(array( ))
                ->setDescription("Importe les contextes de publication dans la base")
                ->setHelp(<<<EOT
La commande <info>kalamu:context:import</info> importe dans la base de donnée les
contextes de publication.

<info>php app/console kalamu:context:import</info>
EOT
                )
                ->setName('kalamu:context:import')
        ;
    }

    /**
     * Coeur de la commande
     */
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
