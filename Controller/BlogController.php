<?php

namespace Gore\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Gore\BlogBundle\Entity\Article;
use Gore\BlogBundle\Entity\Keyword;


class BlogController extends Controller
{
    
    
    /**
     * getCommonData
     * Get data common to many pages on the blog
     */
    private function getCommonData(){
        return $this->get('gore_blog.blog_manager')->getCommonData();
    }

    
    
    /**
     * indexAction
     * Homepage action
     * @return type
     */
    public function indexAction(){
        // getting articles
        $mains  = $this->get('gore_blog.blog_manager')
                       ->getMainArticles();
        $olders = $this->get('gore_blog.blog_manager')
                       ->getOlderArticles();
        
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
        $mgr = $this->get('gore_blog.blog_manager');
        
        $previousArticle    = $mgr->getPreviousArticleFromGivenOne($article);
        $nextArticle        = $mgr->getNextArticleFromGivenOne($article);
        
        return $this->render('GoreBlogBundle:Article:article.html.twig', array(
            'commonData'    => $this->getCommonData(),
            'article'       => $article,
            'previous'      => $previousArticle,
            'next'          => $nextArticle
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
                // returns 0 if POST param is NaN
                $page = intval($request->request->get('page')); 
                if ($page === 0) $page = 1;
                
                $articles = $this->get('gore_blog.blog_manager')
                                 ->getOlderArticles($page);
                
                if (count($articles) > 0){
                    $view = 'GoreBlogBundle:Article:thumbnails.html.twig';
                    return $this->render($view, array(
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
        $view = 'GoreBlogBundle:StaticPages:about-me.html.twig';
        return $this->render($view, array(
            'commonData'        => $this->getCommonData(),
        ));
    }
    
    
    
    /**
     * showTagArticlesAction
     * Show articles containing one tag or category
     * @param \Gore\BlogBundle\Controller\Keyword $keyword
     */
    public function showTagArticlesAction(Keyword $keyword = null){
        if ($keyword === null){
            return $this->showSearchPage();
        }
        return $this->showSearchPage($keyword->getArticles());
    }
    
    
    
    /**
     * showSearchPage
     * Show the homepage but small article are not the older but the result 
     * of a research
     * @param type $articles
     * @return type
     */
    public function showSearchPage($articles = null){
        // mains articles are also displayed on the search page
        $mains  = $this->get('gore_blog.blog_manager')
                       ->getMainArticles();
        
        return $this->render('GoreBlogBundle:Blog:index.html.twig', array(
            'commonData'        => $this->getCommonData(),
            'mains'             => $mains,
            'olders'            => $articles,
            'isSearch'          => true
        ));
    }
}
