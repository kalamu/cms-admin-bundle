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


use Knp\Menu\FactoryInterface;
use Knp\Menu\Provider\MenuProviderInterface;
use Knp\Menu\ItemInterface;
use Knp\Menu\MenuItem;
use Kalamu\CmsAdminBundle\Entity\MenuItem as KalamuMenuItem;

/**
 * Menu generator with knp menu
 */
class MenuProvider implements MenuProviderInterface
{

    protected $menus;

    /**
     *
     * @var \Knp\Menu\FactoryInterface
     */
    protected $factory;

    protected $em;

    public function __construct($menus, FactoryInterface $factory, $doctrine){
        $this->menus = $menus;
        $this->factory = $factory;
        $this->em = $doctrine->getManager();
    }

    public function get($name, array $options = array()){
        $KnpMenu = $this->factory->createItem('root');

        // used to distinguish wen $name is the slug or the place
        if(false === strpos($name, 'slug:')){
            $attr = 'place';
        }else{
            $attr = 'slug';
            $name = str_replace('slug:', '', $name);
        }

        $Menu = $this->em->getRepository('KalamuCmsAdminBundle:Menu')->findOneBy(array($attr => $name));
        if($Menu){
            foreach($Menu->getTopMenuItems() as $item){
                $this->createItem($KnpMenu, $item);
            }
        }

        return $KnpMenu;
    }

    public function has($name, array $options = array()){
        if(false === strpos($name, 'slug:')){
            return isset($this->menus[$name]);
        }

        $menu = $this->em->getRepository('KalamuCmsAdminBundle:Menu')->findOneBy(array('slug' => str_replace('slug:', '', $name)));
        return $menu ? true : false;
    }

    protected function createItem(ItemInterface $KnpMenu, KalamuMenuItem $KalamuMenuItem){
        $Item = new MenuItem('item-'.$KalamuMenuItem->getId(), $this->factory);

        $Item->setLabel($KalamuMenuItem->getTitle());
        if($KalamuMenuItem->getIcon()){
            $Item->setAttribute('icon', 'fa fa-fw '.$KalamuMenuItem->getIcon());
        }
        if($KalamuMenuItem->getCssClass()){
            $Item->setAttribute('class', $KalamuMenuItem->getCssClass());
        }

        $Item->setUri($KalamuMenuItem->getUrl());

        if($KalamuMenuItem->getChildren()->count()){
            foreach($KalamuMenuItem->getChildren() as $child){
                $this->createItem($Item, $child);
            }
        }

        $KnpMenu->addChild($Item);
    }
}