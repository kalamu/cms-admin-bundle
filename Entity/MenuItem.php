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
 * MenuItem
 */
class MenuItem implements \JsonSerializable
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
    private $url;

    /**
     * @var string
     */
    private $icon;

    /**
     * @var string
     */
    private $content_type;

    /**
     * @var array
     */
    private $content_id;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $children;

    /**
     * @var \Kalamu\CmsAdminBundle\Entity\MenuItem
     */
    private $parent;

    /**
     * @var \Kalamu\CmsAdminBundle\Entity\Menu
     */
    private $menu;

    /**
     * @var string
     */
    private $type_label;

    /**
     * @var string
     */
    private $css_class;

    /**
     * @var string
     */
    private $content_context;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->children = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function __toString(){
        return sprintf('%s', $this->getTitle());
    }

    /**
     *
     */
    public function jsonSerialize() {
        return array(
            'id'            => $this->getId(),
            'title'         => $this->getTitle(),
            'icon'          => $this->getIcon(),
            'class'         => $this->getCssClass(),
            'url'           => $this->getUrl(),
            'type'          => $this->getTypeLabel(),
            'content_type'  => $this->getContentType(),
            'content_id'    => $this->getContentId(),
            'children'      => $this->getChildren()->toArray(),
            'context'       => $this->getContentContext(),
        );
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
     * @return MenuItem
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
     * Set url
     *
     * @param string $url
     *
     * @return MenuItem
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set icon
     *
     * @param string $icon
     *
     * @return MenuItem
     */
    public function setIcon($icon)
    {
        $this->icon = $icon;

        return $this;
    }

    /**
     * Get icon
     *
     * @return string
     */
    public function getIcon()
    {
        return $this->icon;
    }

    /**
     * Set contentType
     *
     * @param string $contentType
     *
     * @return MenuItem
     */
    public function setContentType($contentType)
    {
        $this->content_type = $contentType;

        return $this;
    }

    /**
     * Get contentType
     *
     * @return string
     */
    public function getContentType()
    {
        return $this->content_type;
    }

    /**
     * Set contentId
     *
     * @param string $contentId
     *
     * @return MenuItem
     */
    public function setContentId($contentId)
    {
        $this->content_id = $contentId;

        return $this;
    }

    /**
     * Get contentId
     *
     * @return string
     */
    public function getContentId()
    {
        return $this->content_id;
    }

    /**
     * Add child
     *
     * @param \Kalamu\CmsAdminBundle\Entity\MenuItem $child
     *
     * @return MenuItem
     */
    public function addChild(\Kalamu\CmsAdminBundle\Entity\MenuItem $child)
    {
        $child->setParent($this);
        $child->setMenu($this->getMenu());
        $this->children[] = $child;

        return $this;
    }

    /**
     * Remove child
     *
     * @param \Kalamu\CmsAdminBundle\Entity\MenuItem $child
     */
    public function removeChild(\Kalamu\CmsAdminBundle\Entity\MenuItem $child)
    {
        $this->children->removeElement($child);
    }

    /**
     * Get children
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * Set parent
     *
     * @param \Kalamu\CmsAdminBundle\Entity\MenuItem $parent
     *
     * @return MenuItem
     */
    public function setParent(\Kalamu\CmsAdminBundle\Entity\MenuItem $parent = null)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent
     *
     * @return \Kalamu\CmsAdminBundle\Entity\MenuItem
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Set menu
     *
     * @param \Kalamu\CmsAdminBundle\Entity\Menu $menu
     *
     * @return MenuItem
     */
    public function setMenu(\Kalamu\CmsAdminBundle\Entity\Menu $menu = null)
    {
        $this->menu = $menu;
        foreach($this->getChildren() as $child){
            $child->setMenu($menu);
        }

        return $this;
    }

    /**
     * Get menu
     *
     * @return \Kalamu\CmsAdminBundle\Entity\Menu
     */
    public function getMenu()
    {
        return $this->menu;
    }
    /**
     * @var integer
     */
    private $order;


    /**
     * Set order
     *
     * @param integer $order
     *
     * @return MenuItem
     */
    public function setOrder($order)
    {
        $this->order = $order;

        return $this;
    }

    /**
     * Get order
     *
     * @return integer
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * Set typeLabel
     *
     * @param string $typeLabel
     *
     * @return MenuItem
     */
    public function setTypeLabel($typeLabel)
    {
        $this->type_label = $typeLabel;

        return $this;
    }

    /**
     * Get typeLabel
     *
     * @return string
     */
    public function getTypeLabel()
    {
        return $this->type_label;
    }

    /**
     * Set cssClass
     *
     * @param string $cssClass
     *
     * @return MenuItem
     */
    public function setCssClass($cssClass)
    {
        $this->css_class = $cssClass;

        return $this;
    }

    /**
     * Get cssClass
     *
     * @return string
     */
    public function getCssClass()
    {
        return $this->css_class;
    }

    /**
     * Set contentContext
     *
     * @param string $contentContext
     *
     * @return MenuItem
     */
    public function setContentContext($contentContext)
    {
        $this->content_context = $contentContext;

        return $this;
    }

    /**
     * Get contentContext
     *
     * @return string
     */
    public function getContentContext()
    {
        return $this->content_context;
    }
}
