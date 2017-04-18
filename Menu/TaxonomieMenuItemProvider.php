<?php

namespace Kalamu\CmsAdminBundle\Menu;


use Kalamu\CmsAdminBundle\Menu\Interfaces\ItemPickerProviderInterface;
use Kalamu\CmsAdminBundle\Menu\BaseTaxonomyItemPicker;
use Kalamu\CmsAdminBundle\Manager\MenuItemPickerManager;
use Doctrine\Common\Persistence\ManagerRegistry;

class TaxonomieMenuItemProvider implements ItemPickerProviderInterface
{

    /**
     * @var \Doctrine\Common\Persistence\ManagerRegistry
     */
    protected $doctrine;

    protected $knp_paginator;

    protected $term_manager;

    public function __construct(ManagerRegistry $doctrine, $knp_paginator, $type_manager) {
        $this->doctrine = $doctrine;
        $this->knp_paginator = $knp_paginator;
        $this->term_manager = $type_manager->getType('term');
    }

    public function registerMenuItemPickers(MenuItemPickerManager $manager){
        $taxonomies = $this->doctrine->getManager()->getRepository('KalamuCmsAdminBundle:Taxonomy')->findAll();
        foreach($taxonomies as $taxonomy){
            $service = new BaseTaxonomyItemPicker($this->doctrine, $this->knp_paginator, $this->term_manager, $taxonomy);
            $manager->registerMenuItemPicker('taxonomy-'.$taxonomy->getSlug(), $taxonomy->getLibelle(), $service);
        }
    }

}

