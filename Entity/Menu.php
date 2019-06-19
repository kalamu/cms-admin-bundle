<?php

/*
 * This file is part of the kalamu/cms-admin-bundle package.
 *
 * (c) ETIC Services
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Kalamu\CmsAdminBundle\Entity;

/**
 * Menu
 */
class Menu
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $place;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $items;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->items = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function __toString(){
        return sprintf('%s', $this->getTitle());
    }

    /**
     * Get first level items as JSON sting
     *
     * @return string
     */
    public function getJsonItems(){
        return json_encode(array_values($this->getTopMenuItems()->toArray()), JSON_PRETTY_PRINT);
    }

    /**
     * Set menu items from a JSON string
     *
     * @param string $json
     */
    public function setJsonItems($json){
        $datas = json_decode($json);

        $this->old_items = clone $this->items;
        $this->items->clear();
        $this->item_iterator = 0;
        foreach($datas as $item_data){
            $this->addItem( $this->generateMenuItem($item_data) );
        }
        // TODO : watch this...
        foreach($this->old_items as $old_item){
            if($old_item->getId() && !$this->items->contains($old_item)){
                $old_item->setTitle('Must be deleted');
                $old_item->setMenu(null);
                $old_item->setParent(null);
                $this->items->add($old_item);
            }
        }
    }

    protected function generateMenuItem($datas){

        if($datas->id){
            foreach($this->old_items as $old_item){
                if($old_item->getId() == $datas->id){
                    $item = $old_item;
                    break;
                }
            }
        }

        if(!isset($item)){
            $item = new MenuItem();
        }

        $item->setMenu($this);
        $item->setParent(null);
        $item->setTitle($datas->title);
        $item->setUrl($datas->url);
        $item->setIcon($datas->icon);
        $item->setCssClass($datas->class);
        $item->setTypeLabel($datas->type);
        $item->setContentType($datas->content_type);
        $item->setContentId($datas->content_id);
        $item->setOrder($this->item_iterator++);
        $item->setContentContext($datas->context);

        foreach($datas->children as $child){
            $child_obj = $this->generateMenuItem($child);
            $item->addChild( $child_obj );
            $this->items->add($child_obj);
        }

        return $item;
    }

    /**
     * Get the top level items
     */
    public function getTopMenuItems(){
        return $this->getItems()->filter(function ($item){
            return $item->getParent() ? false : true;
        });
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return Menu
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set place
     *
     * @param string $place
     *
     * @return Menu
     */
    public function setPlace($place)
    {
        $this->place = $place;

        return $this;
    }

    /**
     * Get place
     *
     * @return string
     */
    public function getPlace()
    {
        return $this->place;
    }

    /**
     * Add item
     *
     * @param \Kalamu\CmsAdminBundle\Entity\MenuItem $item
     *
     * @return Menu
     */
    public function addItem(\Kalamu\CmsAdminBundle\Entity\MenuItem $item)
    {
        $item->setMenu($this);
        $this->items[] = $item;

        return $this;
    }

    /**
     * Remove item
     *
     * @param \Kalamu\CmsAdminBundle\Entity\MenuItem $item
     */
    public function removeItem(\Kalamu\CmsAdminBundle\Entity\MenuItem $item)
    {
        $this->items->removeElement($item);
    }

    /**
     * Get items
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getItems()
    {
        return $this->items;
    }
    /**
     * @var string
     */
    private $slug;


    /**
     * Set slug
     *
     * @param string $slug
     *
     * @return Menu
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }
}
