<?php

namespace Gore\BlogBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class GoreBlogExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        // putting some of our config.yml parameters accessibles from the ServiceContainer
        $container->setParameter('gore_blog.pictures_folder',           $config['pictures_folder']);
        $container->setParameter('gore_blog.blog_title',                $config['blog_title']);
        $container->setParameter('gore_blog.main_articles_to_show',     $config['main_articles_to_show']);
        $container->setParameter('gore_blog.small_articles_to_show',    $config['small_articles_to_show']);
        
        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
    }
    
}
