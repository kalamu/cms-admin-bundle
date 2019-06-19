<?php

/*
 * This file is part of the kalamu/cms-admin-bundle package.
 *
 * (c) ETIC Services
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Kalamu\CmsAdminBundle\Twig;

use Kalamu\CmsCoreBundle\Manager\ContentTypeManager;
use Kalamu\CmsCoreBundle\Manager\ContextManager;
use Kalamu\DynamiqueConfigBundle\Container\ParameterContainer;

/**
 * Twig Extension to add CMS functions
 */
class CmsExtension extends \Twig_Extension
{

    /**
     * @var \Kalamu\CmsAdminBundle\Manager\ContentTypeManager
     */
    protected $contentTypeManager;

    /**
     * @var Kalamu\CmsAdminBundle\Manager\ContextManager
     */
    protected $contextManager;

    /**
     * @var \Kalamu\DynamiqueConfigBundle\Container\ParameterContainer
     */
    protected $parameterContainer;

    public function __construct(ContentTypeManager $ContentTypeManager, ContextManager $ContextManager, ParameterContainer $ParameterContainer) {
        $this->contentTypeManager = $ContentTypeManager;
        $this->contextManager = $ContextManager;
        $this->parameterContainer = $ParameterContainer;
    }

    public function getFunctions() {
        return array(
            new \Twig_SimpleFunction('cms_current_context', array($this->contextManager, 'getCurrentContext')),
            new \Twig_SimpleFunction('cms_link', array($this, 'cmsLink')),
            new \Twig_SimpleFunction('cms_config', array($this, 'cmsConfig')),
        );
    }

    /**
     * Get the link of a content
     *
     * @param string $base_route
     * @param string|object $content
     * @param array $parameters
     * @param int $referenceType
     * @return string
     * @throws \InvalidArgumentException
     */
    public function cmsLink($base_route, $content, $parameters = array(), $referenceType = null){
        if(is_string($content)){
            $manager = $this->contentTypeManager->getType($content);
        }else{
            $manager = $this->contentTypeManager->getManagerForContent($content);
        }

        switch($base_route){
            case 'read' :
                if(is_string($content)){
                    $content = $manager->getObjectByIdentifier($parameters['identifier']);
                    if(!isset($parameters['_context']) || empty($parameters['_context'])){
                        $parameters['_context'] = $manager->getObjectDefaultContext($content);
                    }

                    $content = $manager->getPublicContent($parameters['identifier'], $parameters['_context']);
                }
                return $manager->getPublicReadLink($content, $parameters, $referenceType);
            case 'index' :
                return $manager->getPublicIndexLink($parameters, $referenceType);
            case 'rss' :
                return $manager->getPublicRssLink($parameters, $referenceType);
            default:
                throw new \InvalidArgumentException(sprintf("The requested route is not managed by the CMS : '%s'", $base_route));
        }
    }

    /**
     * Get a config parameter from the dynamique config container
     *
     * @param string $parameter le nom du paramètre
     * @param mixed $default
     */
    public function cmsConfig($parameter, $default = null){
        return $this->parameterContainer->get($parameter, $default);
    }

    public function getName(){
        return 'cms_extension';
    }
}