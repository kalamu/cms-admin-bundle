<?php

namespace Kalamu\CmsAdminBundle\Twig;

use Roho\CmsBundle\Manager\ContentTypeManager;
use Roho\CmsBundle\Manager\ContextManager;
use Roho\DynamiqueConfigBundle\Container\ParameterContainer;

/**
 * Extension Twig pour ajouter les fonctions du CMS
 */
class CmsExtension extends \Twig_Extension
{

    /**
     * Manager des types de contenu
     * @var \Kalamu\CmsAdminBundle\Manager\ContentTypeManager
     */
    protected $contentTypeManager;

    /**
     * Manager des contextes
     * @var Kalamu\CmsAdminBundle\Manager\ContextManager
     */
    protected $contextManager;

    /**
     * Container des paramètres de configuration dynamique
     * @var \Roho\DynamiqueConfigBundle\Container\ParameterContainer
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
     * Retourne le lien demandé
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
                return $manager->getPublicReadLink($content, $parameters, $referenceType);
            case 'index' :
                return $manager->getPublicIndexLink($parameters, $referenceType);
            case 'rss' :
                return $manager->getPublicRssLink($parameters, $referenceType);
            default:
                throw new \InvalidArgumentException(sprintf("La route demandée n'est pas géré par le CMS : '%s'", $base_route));
        }
    }

    /**
     * Retourne un paramètre de configuration (dynamique config)
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