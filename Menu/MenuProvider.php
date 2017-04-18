<?php

namespace Kalamu\CmsAdminBundle\Menu;


use Knp\Menu\FactoryInterface;
use Knp\Menu\Provider\MenuProviderInterface;
use Knp\Menu\ItemInterface;
use Knp\Menu\MenuItem;
use Kalamu\CmsAdminBundle\Entity\MenuItem as RohoMenuItem;

/**
 * Service de génération des menus pour KNP
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

        // Permet de différentier les menu appelés par la place de ceux appelés par le slug
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

    protected function createItem(ItemInterface $KnpMenu, RohoMenuItem $RohoMenuItem){
        $Item = new MenuItem('item-'.$RohoMenuItem->getId(), $this->factory);

        $Item->setLabel($RohoMenuItem->getTitle());
        if($RohoMenuItem->getIcon()){
            $Item->setAttribute('icon', 'fa fa-fw '.$RohoMenuItem->getIcon());
        }
        if($RohoMenuItem->getCssClass()){
            $Item->setAttribute('class', $RohoMenuItem->getCssClass());
        }

        $Item->setUri($RohoMenuItem->getUrl());

        if($RohoMenuItem->getChildren()->count()){
            foreach($RohoMenuItem->getChildren() as $child){
                $this->createItem($Item, $child);
            }
        }

        $KnpMenu->addChild($Item);
    }
}