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
 * Taxonomy
 */
class Taxonomy
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $libelle;

    /**
     * @var string
     */
    private $slug;

    /**
     * @var string
     */
    private $content_type;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $terms;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->terms = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function __toString() {
        return sprintf('%s', $this->getLibelle());
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
     * Set libelle
     *
     * @param string $libelle
     *
     * @return Taxonomy
     */
    public function setLibelle($libelle)
    {
        $this->libelle = $libelle;

        return $this;
    }

    /**
     * Get libelle
     *
     * @return string
     */
    public function getLibelle()
    {
        return $this->libelle;
    }

    /**
     * Set slug
     *
     * @param string $slug
     *
     * @return Taxonomy
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

    /**
     * Set contentType
     *
     * @param string $contentType
     *
     * @return Taxonomy
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
     * Add term
     *
     * @param Term $term
     *
     * @return Taxonomy
     */
    public function addTerm(Term $term)
    {
        $this->terms[] = $term;

        return $this;
    }

    /**
     * Remove term
     *
     * @param Term $term
     */
    public function removeTerm(Term $term)
    {
        $this->terms->removeElement($term);
    }

    /**
     * Get terms
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTerms()
    {
        return $this->terms;
    }
}
