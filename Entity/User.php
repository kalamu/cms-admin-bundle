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

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use FOS\UserBundle\Model\GroupInterface;
use FOS\UserBundle\Model\User as BaseUser;

/**
 * User
 */
class User extends BaseUser {

    /**
     * @var integer
     */
    protected $id;

    /**
     * @var Collection
     */
    protected $groups;

    /**
     * @var array
     */
    protected $attrs;

    /**
     * Constructor
     */
    public function __construct() {
        parent::__construct();
        $this->groups = new ArrayCollection();
        $this->attrs = array();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    public function getRoles() {
        $roles = [];
        $roles[] = static::ROLE_DEFAULT;
        $roles[] = 'ROLE_ADMIN';
        $roles[] = 'ROLE_SONATA_ADMIN';

        foreach ($this->getGroups() as $group) {
            $roles = array_merge($roles, (array) $group->getRoles());
        }

        return array_unique(array_merge($roles, $this->roles));
    }

    /**
     * Get groups
     *
     * @return Collection
     */
    public function getGroups() {
        return $this->groups;
    }

    /**
     * Add groups
     *
     * @param Group $group
     * @return User
     */
    public function addGroup(GroupInterface $group)
    {
        $this->groups[] = $group;

        return $this;
    }

    /**
     * Remove groups
     *
     * @param Group $groups
     */
    public function removeGroup(GroupInterface $group)
    {
        $this->groups->removeElement($group);
    }

    /**
     * Get attrs
     *
     * @return array
     */
    public function getAttrs() {
        if(!is_array($this->attrs)){
            $this->attrs = array();
        }
        return $this->attrs;
    }

    public function hasAttr($key){
        if(!is_array($this->attrs)){
            $this->attrs = array();
        }
        return array_key_exists($key, $this->attrs);
    }

    public function getAttr($key){
        return $this->attrs[$key];
    }

    /**
     * Add attrs
     *
     * @param string $key
     * @param string $value
     * @return User
     */
    public function addAttr($key, $value)
    {
        if(!is_array($this->attrs)){
            $this->attrs = array();
        }
        $this->attrs[$key] = $value;

        return $this;
    }

    /**
     * Remove attrs
     *
     * @param string $key
     */
    public function removeAttr($key)
    {
        if($this->hasAttr($key)){
            unset($this->attrs[$key]);
        }
    }

    /**
     * Set attrs
     *
     * @param array $attrs
     *
     * @return User
     */
    public function setAttrs($attrs)
    {
        $this->attrs = $attrs;

        return $this;
    }
}
