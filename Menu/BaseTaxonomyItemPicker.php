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

/**
 * ItemPicker for taxonomies
 */
class BaseTaxonomyItemPicker implements MenuItemPickerInterface
{

    /**
     * @var \Knp\Bundle\PaginatorBundle\Pagination\SlidingPagination
     */
    protected $knp_paginator;

    /**
     * @var \Doctrine\Common\Persistence\ManagerRegistry
     */
    protected $doctrine;

    protected $taxonomy;

    protected $manager;

    public function __construct(ManagerRegistry $doctrine, $knp_paginator, $term_manager, $taxonomy) {
        $this->doctrine = $doctrine;
        $this->knp_paginator = $knp_paginator;
        $this->taxonomy = $taxonomy;
        $this->manager = $term_manager;
    }

    public function getManager() {
        return $this->manager;
    }

    /**
     * @param int $page
     * @param int $limit
     * @return array
     */
    public function getItems($page, $limit = 10, $context = null, $search = ''){
        $baseQuery = $this->getBaseQuery();
        if(strlen($search)){
            $baseQuery->andWhere('lower(t.libelle) LIKE :search')
                    ->setParameter('search', '%'.strtolower($search).'%');
        }

        $paginator = $this->knp_paginator->paginate($baseQuery, $page, $limit);

        $infos = array(
            'nb_content'    => $paginator->getTotalItemCount(),
            'nb_pages'      => $paginator->getPageCount(),
            'contents'      => array()
        );

        foreach($paginator as $term){
            $infos['contents'][] = $this->formatItem($term);
        }

        return $infos;
    }

    public function getItem($id, $context = null){
        $baseQuery = $this->getBaseQuery();
        $content = $baseQuery->andWhere('t.id = :id')
                ->setParameter('id', $id)
                ->getQuery()
                ->getOneOrNullResult();

        if($content){
            return $this->formatItem($content);
        }else{
            return array();
        }
    }

    protected function formatItem($term){
        return array(
                'id'    => $term->getId(),
                'title' => strval($term),
                'identifier'    => $this->manager->getObjectIdentifier($term),
                'url'   => $this->manager->getPublicReadLink($term),
            );
    }

    protected function getBaseQuery(){
        return $this->doctrine->getManager()->getRepository('KalamuCmsAdminBundle:Term')
                ->createQueryBuilder('t')
                ->where('t.taxonomy = :tax')
                ->setParameter(':tax', $this->taxonomy)
                ->orderBy('t.libelle', 'asc');
    }
}