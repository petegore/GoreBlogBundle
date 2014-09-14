<?php

namespace Gore\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Gore\BlogBundle\Entity\Article;


class BlogController extends Controller
{
    
    
    /**
     * getCommonData
     * Get data common to many pages on the blog. For example : 
     *      > the blog title
     *      > the pictures folder
     *      > sidebar data
     *      > etc...
     */
    private function getCommonData(){
        // Parameters
        $picturesFolder = $this->container->getParameter('gore_blog.pictures_folder');
        $blogTitle      = $this->container->getParameter('gore_blog.blog_title');
        
        // tags cloud data
        $tags = $this->get('gore_blog.articles_manager')->getTagsCloudData();
        
        $commonData = array(
            'blogTitle'         => $blogTitle,
            'picturesFolder'    => $picturesFolder,
            'tagsCloudTags'     => $tags
        );
        
        return $commonData;
    }

    
    public function indexAction(){
        // getting articles
        $mains      = $this->get('gore_blog.articles_manager')->getMainArticles();
        $olders     = $this->get('gore_blog.articles_manager')->getOlderArticles();
        
        return $this->render('GoreBlogBundle:Blog:index.html.twig', array(
            'commonData'        => $this->getCommonData(),
            'mains'             => $mains,
            'olders'            => $olders
        ));
    }
    
    
    
    /**
     * articleAction
     * Affiche la page d'un article en particulier
     * @param \Gore\BlogBundle\Entity\Article $article
     */
    public function articleAction(Article $article){
        return $this->render('GoreBlogBundle:Article:article.html.twig', array(
            'commonData'        => $this->getCommonData(),
            'article'           => $article
        ));
    }
    
    
    
    /**
     * AJAX
     * loadMoreAction
     * Called by POST AJAX request from Javascript
     */
    public function loadMoreAction(Request $request){
        if ($request->getMethod() === "POST"){
            if ($request->request->get('page')){
                $page = intval($request->request->get('page')); // return 0 if POST param is NaN
                if ($page === 0) $page = 1;
                
                $articles = $this->get('gore_blog.articles_manager')->getOlderArticles($page);
                if (count($articles) > 0){
                    return $this->render('GoreBlogBundle:Article:thumbnails.html.twig', array(
                        'commonData'        => $this->getCommonData(),
                        'articles'          => $articles,
                    ));
                }
                return new Response("204");
            }
            return new Response("400");
        }
        return new Response("405");
    }
    
    
    
    /**
     * aboutMeAction
     * Display the static page "about me"
     * @return type
     */
    public function aboutMeAction(){
        return $this->render('GoreBlogBundle:StaticPages:about-me.html.twig', array(
            'commonData'        => $this->getCommonData(),
        ));
    }
}
