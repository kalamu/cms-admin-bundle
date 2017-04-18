<?php
namespace Kalamu\CmsAdminBundle\Manager;

use Kalamu\CmsAdminBundle\Menu\Interfaces\MenuItemPickerInterface;
use Kalamu\CmsAdminBundle\Menu\Interfaces\ItemPickerProviderInterface;

/**
 * Manager en charge de fournir les infos pour la page de création des menus
 */
class MenuItemPickerManager
{

    protected $labels = array();

    protected $services = array();

    protected $providers = array();

    protected $providers_loaded = false;

    /**
     * Enregistre un service ItemPicker
     * @param string $name
     * @param string $label
     * @param MenuItemPickerInterface $service
     */
    public function registerMenuItemPicker($name, $label, MenuItemPickerInterface $service){
        $this->labels[$name] = $label;
        $this->services[$name] = $service;
    }

    /**
     * Enregistre un service provider d'ItemPicker
     */
    public function registerPickerProvider(ItemPickerProviderInterface $provider){
        $this->providers[] = $provider;
        $this->providers_loaded = false;
    }

    /**
     * Retourne les labels des item pickers
     * @return array
     */
    public function getItemPickers(){
        $this->importProviders();
        return $this->labels;
    }

    /**
     * Retourne le service picker
     * @param string $name
     * @return \Kalamu\CmsAdminBundle\Menu\Interfaces\MenuItemPickerInterface
     */
    public function getItemPicker($name){
        $this->importProviders();
        return $this->services[$name];
    }

    /**
     * Retourne les items d'un type
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
     * Importe les providers
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