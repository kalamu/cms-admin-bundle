<?php

namespace Kalamu\CmsAdminBundle\Entity;

use Roho\CmsBundle\ContentType\Interfaces\NestableInterface;
use Roho\CmsBundle\ContentType\Interfaces\CaracterizableInterface;
use Roho\CmsBundle\ContentType\Interfaces\IllustrableInterface;

use Roho\CmsBundle\ContentType\Traits\NestableTrait;
use Roho\CmsBundle\ContentType\Traits\CaracterizableTrait;
use Roho\CmsBundle\ContentType\Traits\IllustrableTrait;

use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * Term
 */
class Term implements NestableInterface, CaracterizableInterface, IllustrableInterface
{

    use NestableTrait;
    use CaracterizableTrait;
    use IllustrableTrait;

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
    private $description;

    /**
     * @var \Kalamu\CmsAdminBundle\Entity\Taxonomy
     */
    private $taxonomy;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->children = new \Doctrine\Common\Collections\ArrayCollection();
    }


    public function __toString() {
        return sprintf('%s', $this->getLibelle());
    }

    public function validate(ExecutionContextInterface $context){

        if($this->getParent()){
            if($this->getParent()->getTaxonomy() != $this->getTaxonomy()){
                $context->buildViolation("Le term parent ne fait pas parti de la même classification que le term courant")
                        ->atPath('parent')
                        ->addViolation();
            }
        }

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
     * @return Term
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
     * @return Term
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
     * Set description
     *
     * @param string $description
     *
     * @return Term
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set taxonomy
     *
     * @param \Kalamu\CmsAdminBundle\Entity\Taxonomy $taxonomy
     *
     * @return Term
     */
    public function setTaxonomy(\Kalamu\CmsAdminBundle\Entity\Taxonomy $taxonomy = null)
    {
        $this->taxonomy = $taxonomy;

        return $this;
    }

    /**
     * Get taxonomy
     *
     * @return \Kalamu\CmsAdminBundle\Entity\Taxonomy
     */
    public function getTaxonomy()
    {
        return $this->taxonomy;
    }
    /**
     * @var string
     */
    private $resume;


    /**
     * Set resume
     *
     * @param string $resume
     *
     * @return Term
     */
    public function setResume($resume)
    {
        $this->resume = $resume;

        return $this;
    }

    /**
     * Get resume
     *
     * @return string
     */
    public function getResume()
    {
        return $this->resume;
    }

}
