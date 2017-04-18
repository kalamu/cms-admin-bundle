<?php

namespace Kalamu\CmsAdminBundle\Twig;


/**
 * Autres extentions twig utilisés
 */
class OtherExtension extends \Twig_Extension
{

    public function getFilters() {
        return array(
            new \Twig_SimpleFilter('json_decode', array($this, 'jsonDecode')),
        );
    }

    public function getFunctions() {
        return array(
            new \Twig_SimpleFunction('uniqid', 'uniqid')
        );
    }

    public function jsonDecode($data){
        return json_decode($data, true);
    }

    public function getName(){
        return 'other_cms_extension';
    }
}