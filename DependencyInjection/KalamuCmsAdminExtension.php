<?php

namespace Kalamu\CmsAdminBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
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

        // Configuration de Twig
        if (isset($bundles['TwigBundle'])) {
            $this->prependConfig($container, 'twig', $this->getTwigConfig());
        }

        // Configuration de Tinymce
        if (isset($bundles['StfalconTinymceBundle'])) {
            $this->prependConfig($container, 'stfalcon_tinymce', $this->getStfalconTinymceConfig());
        }

        // Configuration de FMElfinderBundle
        if(isset($bundles['FMElfinderBundle'])){
            $container->setParameter('twig.form.resources', "KalamuCmsAdminBundle:Form:elfinder.html.twig");
        }
    }

    /**
     * Retourne la config de Twig
     * @return array
     */
    protected function getTwigConfig(){
        return array(
            'form' => array('resources' => array(
                'KalamuCmsAdminBundle:Form:wysiwyg.html.twig',
                'KalamuCmsAdminBundle:Form:wysiwyg_dashboard.html.twig',
                'KalamuCmsAdminBundle:Form:elfinder.html.twig',
                'KalamuCmsAdminBundle:Form:classifiable.html.twig',
                )),
            'globals' => array(
                'tinymce_included' => false
            )
        );
    }

    /**
     * Retourne la config de StfalconTinymce
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
                    )
                ),
                'theme'         => array(
                    'simple'        => array(
                        'plugins'   => 'table link kalamuLink hr searchreplace fullscreen paste',
                        'toolbar'  => 'undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | kalamuLink link unlink '
                    )
                )
            )
        );
    }

    /**
     * Met à jour la config pour un bundle
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
