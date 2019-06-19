<?php

/*
 * This file is part of the kalamu/cms-admin-bundle package.
 *
 * (c) ETIC Services
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Kalamu\CmsAdminBundle\Menu;

use Kalamu\CmsAdminBundle\Menu\Interfaces\MenuItemPickerInterface;
use Doctrine\Common\Persistence\ManagerRegistry;
use Kalamu\CmsCoreBundle\Manager\ContentTypeManager;

/**
 * Menu picker pour sélectionner les index des types de contenu
 */
class IndexMenuItemPicker implements MenuItemPickerInterface
{

    /**
     * @var \Doctrine\Common\Persistence\ManagerRegistry
     */
    protected $doctrine;

    /**
     * @var \Knp\Bundle\PaginatorBundle\Pagination\SlidingPagination
     */
    protected $knp_paginator;

    /**
     * @var \Kalamu\CmsAdminBundle\Manager\ContentTypeManager
     */
    protected $ContentTypeManager;

    protected $router;

    protected $custom_index = array();

    public function __construct(ManagerRegistry $doctrine, $knp_paginator, ContentTypeManager $ContentTypeManager, $router) {
        $this->doctrine = $doctrine;
        $this->knp_paginator = $knp_paginator;
        $this->ContentTypeManager = $ContentTypeManager;
        $this->router = $router;
    }

    public function registerCustomIndex($infos){
        $this->custom_index[] = $infos;
    }

    /**
     * Retourne une page d'items pour l'Item picker
     * @param int $page
     * @param int $limit
     * @return array
     */
    public function getItems($page, $limit = 10, $context = null, $search = ''){

        $infos = array();

        foreach($this->custom_index as $index){
            $infos[] = array(
                'title'     => $index['label'],
                'url'       => isset($index['url']) ? $index['url'] : $this->router->generate($index['route'])
            );
        }

        foreach($this->ContentTypeManager->getLabels() as $name => $label){
            $manager = $this->ContentTypeManager->getType($name);
            if($manager->hasIndex()){
                $infos[] = array(
                    'title' => 'Index des '.$label,
                    'url'   => $manager->getPublicIndexLink(),
                    'type'  => $name
                );
            }

            foreach($manager->getContexts() as $context){
                if(!$manager->hasIndex($context)){
                    continue;
                }
                $Context = $this->doctrine->getManager()->getRepository('KalamuCmsAdminBundle:ContextPublication')->findOneByName($context);
                $infos[] = array(
                    'title' => 'Index des '.$label.' du context '.$Context,
                    'url'   => $manager->getPublicIndexLink(array('_context' => $context)),
                    'type'  => $name,
                    'context' => $context
                );
            }
        }

        if(strlen($search)){
            foreach($infos as $i => $index){
                if(!preg_match('#'.preg_quote($search, '#').'#i', $index['title'])){
                    unset($infos[$i]);
                }
            }
        }

        $paginator = $this->knp_paginator->paginate($infos, $page, $limit);

        return array(
            'nb_contenu'    => $paginator->getTotalItemCount(),
            'nb_pages'      => $paginator->getPageCount(),
            'contenus'      => array_slice($infos, (($page-1)*$limit), $limit)
        );
    }

    public function getItem($id, $context = null) {
    }

    public function getManager(){
    }
}

