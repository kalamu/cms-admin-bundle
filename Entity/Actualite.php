<?php

namespace Kalamu\CmsAdminBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Roho\CmsBundle\ContentType\Interfaces\ClassifiableInterface;
use Roho\CmsBundle\ContentType\Interfaces\ContextualizableInterface;
use Roho\CmsBundle\ContentType\Interfaces\IllustrableInterface;
use Roho\CmsBundle\ContentType\Interfaces\PublishStatusInterface;
use Roho\CmsBundle\ContentType\Interfaces\PublishTimestampInterface;
use Roho\CmsBundle\ContentType\Interfaces\TemplateableInterface;
use Roho\CmsBundle\ContentType\Traits\ClassifiableTrait;
use Roho\CmsBundle\ContentType\Traits\ContextualizableTrait;
use Roho\CmsBundle\ContentType\Traits\IllustrableTrait;
use Roho\CmsBundle\ContentType\Traits\PublishTimestampTrait;
use Roho\CmsBundle\ContentType\Traits\TemplateableTrait;
use Roho\CmsBundle\Model\ContentTypeInterface;

/**
 * Actualite
 */
class Actualite implements ContentTypeInterface, PublishStatusInterface, TemplateableInterface, IllustrableInterface, ClassifiableInterface, PublishTimestampInterface, ContextualizableInterface
{

    use TemplateableTrait;
    use IllustrableTrait;
    use ClassifiableTrait;
    use PublishTimestampTrait;
    use ContextualizableTrait;

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
    private $slug;

    /**
     * @var string
     */
    private $contenu;

    /**
     * @var \DateTime
     */
    private $created_at;

    /**
     * @var \DateTime
     */
    private $updated_at;

    /**
     * @var string
     */
    private $created_by;

    /**
     * @var string
     */
    private $updated_by;

    /**
     * @var array
     */
    private $metas;

    /**
     * @var string
     */
    private $resume;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->terms = new ArrayCollection();
        $this->context_publication = new ArrayCollection();
    }

    public function __toString() {
        return sprintf('%s', $this->getTitle());
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
     * @return Actualite
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
     * Set slug
     *
     * @param string $slug
     *
     * @return Actualite
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
     * Set contenu
     *
     * @param string $contenu
     *
     * @return Actualite
     */
    public function setContenu($contenu)
    {
        $this->contenu = $contenu;

        return $this;
    }

    /**
     * Get contenu
     *
     * @return string
     */
    public function getContenu()
    {
        return $this->contenu;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Actualite
     */
    public function setCreatedAt($createdAt)
    {
        $this->created_at = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return Actualite
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updated_at = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updated_at;
    }

    /**
     * Set createdBy
     *
     * @param string $createdBy
     *
     * @return Actualite
     */
    public function setCreatedBy($createdBy)
    {
        $this->created_by = $createdBy;

        return $this;
    }

    /**
     * Get createdBy
     *
     * @return string
     */
    public function getCreatedBy()
    {
        return $this->created_by;
    }

    /**
     * Set updatedBy
     *
     * @param string $updatedBy
     *
     * @return Actualite
     */
    public function setUpdatedBy($updatedBy)
    {
        $this->updated_by = $updatedBy;

        return $this;
    }

    /**
     * Get updatedBy
     *
     * @return string
     */
    public function getUpdatedBy()
    {
        return $this->updated_by;
    }

    /**
     * Set metas
     *
     * @param array $metas
     *
     * @return Actualite
     */
    public function setMetas($metas)
    {
        $this->metas = $metas;

        return $this;
    }

    /**
     * Get metas
     *
     * @return array
     */
    public function getMetas()
    {
        return $this->metas;
    }

    /**
     * Set resume
     *
     * @param string $resume
     *
     * @return Actualite
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
