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
use Kalamu\CmsCoreBundle\Manager\Interfaces\ContentManagerInterface;

/**
 * Default ItemPicker
 */
class DefaultMenuItemPicker implements MenuItemPickerInterface
{

    /**
     * @var \Knp\Bundle\PaginatorBundle\Pagination\SlidingPagination
     */
    protected $knp_paginator;

    /**
     * @var \Kalamu\CmsAdminBundle\Manager\Interfaces\ContentManagerInterface
     */
    protected $manager;

    public function __construct($knp_paginator) {
        $this->knp_paginator = $knp_paginator;
    }

    /**
     * Set the content manager
     *
     * @param ContentManagerInterface $manager
     */
    public function setManager(ContentManagerInterface $manager){
        $this->manager = $manager;
    }

    /**
     * Get the content manager
     *
     * @return \Kalamu\CmsAdminBundle\Manager\Interfaces\ContentManagerInterface
     */
    public function getManager() {
        return $this->manager;
    }

    /**
     * Get a list of items
     *
     * @param int $page
     * @param int $limit
     * @return array
     */
    public function getItems($page, $limit = 10, $context = null, $search = ''){
        $baseQuery = $this->getBaseQuery($context);

        if(strlen($search)){
            $baseQuery
                    ->andWhere('lower(c.'.$this->manager->getStringifier().') LIKE :search')
                    ->setParameter('search', '%'.strtolower($search).'%');
        }

        $paginator = $this->knp_paginator->paginate($baseQuery, $page, $limit);

        $infos = array(
            'nb_contenu'    => $paginator->getTotalItemCount(),
            'nb_pages'      => $paginator->getPageCount(),
            'contenus'      => array()
        );

        foreach($paginator as $content){
            $infos['contenus'][] = $this->formatItem($content, $context);
        }

        return $infos;
    }

    public function getItem($id, $context = null){
        $baseQuery = $this->getBaseQuery($context);
        $content = $baseQuery->andWhere('c.id = :id')
                ->setParameter('id', $id)
                ->getQuery()
                ->getOneOrNullResult();

        if($content){
            return $this->formatItem($content, $context);
        }else{
            return array();
        }
    }

    /**
     * Format the informations for an item
     *
     * @param type $content
     * @param type $context
     * @return type
     */
    protected function formatItem($content, $context = null){
        $content_infos = array(
            'id'    => $content->getId(),
            'title' => strval($content),
            'identifier'    => $this->manager->getObjectIdentifier($content),
        );

        if($this->contextEnabled()){
            if(!$context){
                // Si le context n'est pas précisé. On prend le context default s'il est présent ou un autre dans le cas contraire
                $contextManager = $this->manager->getContextManager();
                $default_context = $contextManager->getDefaultContext();

                foreach($content->getContextPublication() as $contentContext){
                    if($contentContext->getName() == $default_context){
                        $context = $default_context;
                        break;
                    }else{
                        $context = $contentContext->getName();
                    }
                }
            }

            $content_infos['url'] = $this->manager->getPublicReadLink($content, array('_context' => $context));
            $content_infos['context'] = $context;
            $content_infos['contexts'] = array();
            foreach($content->getContextPublication() as $contextPublication){
                $content_infos['contexts'][$contextPublication->getName()] = $contextPublication->getTitle();
            }
        }else{
            $content_infos['url'] = $this->manager->getPublicReadLink($content);
        }

        return $content_infos;
    }

    protected function getBaseQuery($context = null){
        $baseQuery = $this->manager->getListQuery();
        if($this->contextEnabled() && $context){
            $baseQuery->leftJoin('c.context_publication', 'context')
                    ->andWhere('context.name = :context')
                    ->setParameter('context', $context);
        }

        return $baseQuery;
    }

    protected function contextEnabled(){
        return (bool) count($this->manager->getContexts());
    }

}