<?php

namespace Kalamu\CmsAdminBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Roho\CmsBundle\ContentType\Interfaces\ContextualizableInterface;
use Roho\CmsBundle\ContentType\Interfaces\PublishStatusInterface;
use Roho\CmsBundle\ContentType\Interfaces\PublishTimestampInterface;
use Roho\CmsBundle\ContentType\Interfaces\TemplateableInterface;
use Roho\CmsBundle\ContentType\Interfaces\NestableInterface;
use Roho\CmsBundle\ContentType\Traits\ContextualizableTrait;
use Roho\CmsBundle\ContentType\Traits\PublishTimestampTrait;
use Roho\CmsBundle\ContentType\Traits\TemplateableTrait;
use Roho\CmsBundle\ContentType\Traits\NestableTrait;
use Roho\CmsBundle\Model\ContentTypeInterface;

/**
 * Page
 */
class Page implements ContentTypeInterface, PublishStatusInterface, TemplateableInterface, PublishTimestampInterface, ContextualizableInterface, NestableInterface
{

    use TemplateableTrait;
    use PublishTimestampTrait;
    use ContextualizableTrait;
    use NestableTrait;

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
    private $contenu;

    /**
     * @var array
     */
    private $metas;

    /**
     * @var string
     */
    private $slug;

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


    public function __toString(){
        return sprintf('%s', $this->getTitle());
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->context_publication = new ArrayCollection();
        $this->children = new ArrayCollection();
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
     * @return Page
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
     * @return Page
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
     * @return Page
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
     * Set metas
     *
     * @param array $metas
     *
     * @return Page
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
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Page
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
     * @return Page
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
     * @return Page
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
     * @return Page
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

}
