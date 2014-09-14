<?php

namespace Gore\BlogBundle\Listener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Gore\BlogBundle\Entity\Article;
use Gore\BlogBundle\Services\ArticlesManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

class PrePersistListener {
    protected $container;
    
    public function __construct(ContainerInterface $ci){
        $this->container = $ci;
    }
    
    
    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        $articlesManager = $this->container->get('gore_blog.articles_manager');

        if ($entity instanceof Article) {
            $entity = $articlesManager->fillPrepersistArticle($entity);
        }
    }
}