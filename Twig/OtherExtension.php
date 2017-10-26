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
            new \Twig_SimpleFunction('uniqid', [$this, 'uniqid'])
        );
    }

    public function jsonDecode($data){
        return json_decode($data, true);
    }

    public function uniqid(){
        return str_replace('.', '-', uniqid("kalamu_", true));
    }

    public function getName(){
        return 'other_cms_extension';
    }
}