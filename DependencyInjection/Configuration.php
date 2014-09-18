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
                ->integerNode('main_articles_to_show')
                    ->defaultValue(2)
                    ->min(0)
                    ->info('number of articles to display in big size on the first page')
                ->end()
                ->integerNode('small_articles_to_show')
                    ->defaultValue(3)
                    ->min(0)
                    ->info('number of small to display on the first page and to load at each new loading')
                ->end()
                ->arrayNode('social_networks_urls')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('email')->defaultNull()->end()
                        ->scalarNode('bitbucket')->defaultNull()->end()
                        ->scalarNode('facebook')->defaultNull()->end()
                        ->scalarNode('github')->defaultNull()->end()
                        ->scalarNode('linkedin')->defaultNull()->end()
                        ->scalarNode('pinterest')->defaultNull()->end()
                        ->scalarNode('tumblr')->defaultNull()->end()
                        ->scalarNode('twitter')->defaultNull()->end()
                    ->end()
                ->end()
                ->arrayNode('tags_cloud')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->integerNode('number_of_keywords')
                            ->info('number of keywords to display in the cloud')
                            ->defaultValue(10)
                            ->min(0)
                        ->end()
                        ->integerNode('min_font_size')
                            ->info('minimum font-size for tag on tags cloud')
                            ->defaultValue(12)
                            ->min(0)
                        ->end()
                        ->integerNode('max_font_size')
                            ->info('maximum font-size for tag on tags cloud')
                            ->defaultValue(20)
                            ->min(0)
                        ->end()
                        ->scalarNode('font_size_unit')
                            ->info('font-size unit to use (em, px, ...) for tag on tags cloud')
                            ->defaultValue('px')
                        ->end()
                        ->booleanNode('shuffle_tags')
                            ->info('shuffle tags or not in the tags cloud')
                            ->defaultTrue()
                        ->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
