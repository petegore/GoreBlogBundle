<?php

namespace Gore\BlogBundle\Listener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\DependencyInjection\ContainerInterface;

use Gore\BlogBundle\Entity\Picture;

class PostLoadListener {
    
    protected $container;
    
    public function __construct(ContainerInterface $container){
        $this->container = $container;
    }
    
    /**
     * postLoad
     * update things after they've been loaded by doctrine
     * @param \Doctrine\ORM\Event\LifecycleEventArgs $args
     */
    public function postLoad(LifecycleEventArgs $args){
        $entity = $args->getEntity();
        $articlesManager = $this->container->get('gore_blog.articles_manager');

        if ($entity instanceof Picture){
            // not used anymore
        }
    }
    
}

?>