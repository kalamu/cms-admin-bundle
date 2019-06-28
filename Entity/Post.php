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
use Kalamu\CmsCoreBundle\ContentType\Interfaces\ClassifiableInterface;
use Kalamu\CmsCoreBundle\ContentType\Interfaces\ContextualizableInterface;
use Kalamu\CmsCoreBundle\ContentType\Interfaces\IllustrableInterface;
use Kalamu\CmsCoreBundle\ContentType\Interfaces\PublishStatusInterface;
use Kalamu\CmsCoreBundle\ContentType\Interfaces\PublishTimestampInterface;
use Kalamu\CmsCoreBundle\ContentType\Interfaces\PublishUntilTimestampInterface;
use Kalamu\CmsCoreBundle\ContentType\Interfaces\TemplateableInterface;
use Kalamu\CmsCoreBundle\ContentType\Traits\ClassifiableTrait;
use Kalamu\CmsCoreBundle\ContentType\Traits\ContextualizableTrait;
use Kalamu\CmsCoreBundle\ContentType\Traits\IllustrableTrait;
use Kalamu\CmsCoreBundle\ContentType\Traits\PublishTimestampTrait;
use Kalamu\CmsCoreBundle\ContentType\Traits\PublishUntilTimestampTrait;
use Kalamu\CmsCoreBundle\ContentType\Traits\TemplateableTrait;
use Kalamu\CmsCoreBundle\Model\ContentTypeInterface;

/**
 * Post
 */
class Post implements ContentTypeInterface, PublishStatusInterface, TemplateableInterface, IllustrableInterface, ClassifiableInterface, PublishTimestampInterface, PublishUntilTimestampInterface, ContextualizableInterface
{

    use TemplateableTrait;
    use IllustrableTrait;
    use ClassifiableTrait;
    use PublishTimestampTrait;
    use ContextualizableTrait;
    use PublishUntilTimestampTrait;

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
    private $content;

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
     * @return Post
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
     * @return Post
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
     * Set content
     *
     * @param string $content
     *
     * @return Post
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Post
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
     * @return Post
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
     * @return Post
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
     * @return Post
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
     * @return Post
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
     * @return Post
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
