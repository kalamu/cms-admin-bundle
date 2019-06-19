<?php

/*
 * This file is part of the kalamu/cms-admin-bundle package.
 *
 * (c) ETIC Services
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Kalamu\CmsAdminBundle\Manager;

use Kalamu\CmsAdminBundle\Menu\Interfaces\MenuItemPickerInterface;
use Kalamu\CmsAdminBundle\Menu\Interfaces\ItemPickerProviderInterface;

/**
 * Manager service that provide informations items for menu builder
 */
class MenuItemPickerManager
{

    protected $labels = array();

    protected $services = array();

    protected $providers = array();

    protected $providers_loaded = false;

    /**
     * add a new ItemPicker service
     *
     * @param string $name
     * @param string $label
     * @param MenuItemPickerInterface $service
     */
    public function registerMenuItemPicker($name, $label, MenuItemPickerInterface $service){
        $this->labels[$name] = $label;
        $this->services[$name] = $service;
    }

    /**
     * Add a new ItemPicker service provider
     */
    public function registerPickerProvider(ItemPickerProviderInterface $provider){
        $this->providers[] = $provider;
        $this->providers_loaded = false;
    }

    /**
     * Get the labels of item pickers
     *
     * @return array
     */
    public function getItemPickers(){
        $this->importProviders();
        return $this->labels;
    }

    /**
     * Get the item picker requested
     *
     * @param string $name
     * @return \Kalamu\CmsAdminBundle\Menu\Interfaces\MenuItemPickerInterface
     */
    public function getItemPicker($name){
        $this->importProviders();
        return $this->services[$name];
    }

    /**
     * Get items of a certain type
     *
     * @param string $name nom du picker
     * @param int $page
     * @param int $limit
     * @return array
     */
    public function getItems($name, $page = 1, $limit = 10, $context = null, $search = ''){
        $this->importProviders();
        return $this->services[$name]->getItems($page, $limit, $context, $search);
    }

    public function getItem($name, $id, $context = null) {
        $this->importProviders();
        return $this->services[$name]->getItem($id, $context);
    }

    /**
     * Imports ItemPickers from providers
     * 
     * @return type
     */
    protected function importProviders(){
        if($this->providers_loaded){
            return;
        }

        foreach($this->providers as $provider){
            $provider->registerMenuItemPickers($this);
        }

        $this->providers_loaded = true;
        $this->providers = array();
    }
}