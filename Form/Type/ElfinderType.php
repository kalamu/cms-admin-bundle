<?php

/*
 * This file is part of the kalamu/cms-admin-bundle package.
 *
 * (c) ETIC Services
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Kalamu\CmsAdminBundle\Form\Type;

use FM\ElfinderBundle\Form\Type\ElFinderType as BaseElfinderType;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ElfinderType extends BaseElfinderType
{
    protected $parameters;

    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);
        $resolver->setRequired('instance');
        $resolver->setRequired('elfinder_select_mode');
    }


    public function setElFinderParameter($parameter){
        $this->parameters = $parameter;
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        parent::buildView($view, $form, $options);
        $params = $this->parameters['instances'][$options['instance']];

//        var_dump($options['instance'], $this->parameters);die();
        $view->vars['prefix'] = $this->parameters['assets_path'];
        $view->vars['theme'] = $params['theme'];
        $view->vars['locale'] = $params['locale'];

        $onlyMimes      = count($params['visible_mime_types'])
                              ? "['".implode("','", $params['visible_mime_types'])."']"
                              : '[]';
        $view->vars['onlyMimes'] = $onlyMimes;
        $view->vars['relative_path'] = $params['relative_path'];
        $view->vars['pathPrefix'] = $params['path_prefix'];
        $view->vars['elfinder_select_mode'] = $options['elfinder_select_mode'];
    }

}