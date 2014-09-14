<?php

namespace Gore\BlogBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('gore_blog');

        // adding the gore_blog allowed parameters in the config.yml file
        $rootNode
            ->children()
                ->scalarNode('pictures_folder')
                    ->defaultValue('pictures')
                    ->info('folder from /web where article pictures will be stored')
                ->end()
                ->scalarNode('blog_title')
                    ->defaultValue('GoreBlog')
                    ->info('name of your blog')
                ->end()
                ->scalarNode('main_articles_to_show')
                    ->defaultValue(1)
                    ->info('number of articles to display in big size on the first page')
                ->end()
                ->scalarNode('small_articles_to_show')
                    ->defaultValue(4)
                    ->info('number of small to display on the first page and to load at each new loading')
                ->end()
            ->end();

        return $treeBuilder;
    }
}
