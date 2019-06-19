<?php

/*
 * This file is part of the kalamu/cms-admin-bundle package.
 *
 * (c) ETIC Services
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Kalamu\CmsAdminBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;

class KalamuCmsAdminExtension extends Extension implements PrependExtensionInterface
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
    }

    public function prepend(ContainerBuilder $container) {
        $configs = $container->getExtensionConfig($this->getAlias());
        $config = $this->processConfiguration(new Configuration(), $configs);
        $bundles = $container->getParameter('kernel.bundles');

        // Twig configuration
        if (isset($bundles['TwigBundle'])) {
            $this->prependConfig($container, 'twig', $this->getTwigConfig());
        }

        // Tinymce configuration
        if (isset($bundles['StfalconTinymceBundle'])) {
            $this->prependConfig($container, 'stfalcon_tinymce', $this->getStfalconTinymceConfig());
        }

        // FMElfinderBundle configuration
        if(isset($bundles['FMElfinderBundle'])){
            $container->setParameter('twig.form.resources', "KalamuCmsAdminBundle:Form:elfinder.html.twig");
        }
    }

    /**
     * @return array
     */
    protected function getTwigConfig(){
        return array(
            'form_themes' => array(
                'KalamuCmsAdminBundle:Form:wysiwyg.html.twig',
                'KalamuCmsAdminBundle:Form:wysiwyg_dashboard.html.twig',
                'KalamuCmsAdminBundle:Form:elfinder.html.twig',
                'KalamuCmsAdminBundle:Form:classifiable.html.twig',
                'KalamuCmsAdminBundle:Form:fields.html.twig'
            ),
            'globals' => array(
                'tinymce_included' => false
            )
        );
    }

    /**
     * @return array
     */
    protected function getStfalconTinymceConfig(){
        return array(
            'include_jquery' => false,
            'tinymce_jquery' => false,
            'tinymce_config' => array(
                'external_plugins' => array(
                    'kalamuLink' => array(
                        'url'   => 'asset[bundles/kalamucmsadmin/js/tinymce_link/plugin.js]'
                    ),
                ),
                'theme'         => array(
                    'simple'        => array(
                        'plugins'   => 'lists advlist table link kalamuLink hr searchreplace fullscreen paste textcolor code',
                        'toolbar1'  => 'undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist | outdent indent ',
                        'toolbar2'  => 'kalamuLink link unlink | forecolor backcolor | code'
                    )
                )
            )
        );
    }

    /**
     * Update the configuration for a bundle
     *
     * @param ContainerBuilder $container
     * @param string $config_root
     * @param array $config
     */
    protected function prependConfig(ContainerBuilder $container, $config_root, $config){
        foreach ($container->getExtensions() as $name => $extension) {
            if ($name == $config_root) {
                $container->prependExtensionConfig($config_root, $config);
            }
        }
    }
}
