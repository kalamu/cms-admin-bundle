<?php

/*
 * This file is part of the kalamu/cms-admin-bundle package.
 *
 * (c) ETIC Services
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Kalamu\CmsAdminBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\DefinitionDecorator;
use Symfony\Component\DependencyInjection\Parameter;
use Symfony\Component\DependencyInjection\Reference;

/**
 * CompilerPass pour l'enregistrement des types de contenu
 */
class CmsCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if($container->has('fm_elfinder.form.type')){
            $definition = $container->findDefinition('fm_elfinder.form.type');
            $definition->addMethodCall('setElFinderParameter', array(new Parameter('fm_elfinder')));
        }

        $this->registerMenuItemPicker($container);
    }

    /**
     * Gère les services de MenuItemPicker
     */
    protected function registerMenuItemPicker(ContainerBuilder $container){
        $activated_types = $container->getParameter('kalamu_cms_core.activated_types');

        foreach ($activated_types as $type) {
            if(!$type['default_menu_item_picker']){ // l'utilisateur ne veux pas du picker par défaut
                continue;
            }

            $manager = new DefinitionDecorator('kalamu_cms_admin.item_picker.base_manager');
            $manager->setArguments(array(new Reference('knp_paginator')));
            $manager->addMethodCall('setManager', array(new Reference($type['manager'])));
            $manager->addTag('kalamu_cms_admin.menu_item_picker', array('alias' => $type['name'], 'label' => $type['label']));

            $container->setDefinition('kalamu_cms_admin.item_picker.'.$type['name'], $manager);
        }

        $menuItemManager = $container->findDefinition('kalamu_cms_admin.menu_item.manager');
        $item_pickers = $container->findTaggedServiceIds('kalamu_cms_admin.menu_item_picker');
        foreach($item_pickers as $service => $item_picker){
            foreach($item_picker as $tag){
                $menuItemManager->addMethodCall('registerMenuItemPicker', array($tag['alias'], $tag['label'], new Reference($service)));
            }
        }

        $item_picker_providers = $container->findTaggedServiceIds('kalamu_cms_admin.menu_item_picker.provider');
        foreach(array_keys($item_picker_providers) as $service){
            $menuItemManager->addMethodCall('registerPickerProvider', array(new Reference($service)));
        }

        // Enregistrement de la route vers la Home si existante
        if($container->hasParameter('kalamu_cms_core.home_route')){
            $indexPicker = $container->findDefinition('kalamu_cms_admin.menu_item.index_picker');
            $indexPicker->addMethodCall('registerCustomIndex', array(array('route' => new Parameter('kalamu_cms_core.home_route'), 'label' => 'Accueil du site')));
        }
    }

}