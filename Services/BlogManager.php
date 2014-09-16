<?php

namespace Gore\BlogBundle\Services;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Gore\BlogBundle\Entity\Article;
use Gore\BlogBundle\Entity\Picture;


class BlogManager extends \Twig_Extension {
    
    protected $doctrine;
    protected $container;
    
    public function __construct(
        \Doctrine\Bundle\DoctrineBundle\Registry $doctrine, 
        ContainerInterface $sc
    ){
        $this->doctrine     = $doctrine;
        $this->container    = $sc;
        $this->repo         = $doctrine->getManager()
                                       ->getRepository('GoreBlogBundle:Article');
    }
    
    
    
    
    /*****************************************
     * == TWIG FUNCTIONS
     *****************************************/
    
    public function getName(){
        return 'gore_twig';
    }
    
    public function getFilters(){
        return array(
            new \Twig_SimpleFilter('readabledate', array($this, 'formatDate')),
        );
    }
    
    /**
     * formatDate
     * Format a datetime to a readable date according to the locale
     * @param \Datetime $date
     */
    public function formatDate(\Datetime $date){
        $locale = $this->container->getParameter('locale');
        $format = "%A, %#d %B %Y";  // windows does not support %e so we put %#d
        if (substr($locale, 0, 2) === "fr"){
            $format = str_replace(',', '', $format);
        }
        return strftime($format, $date->getTimestamp());
    }
    
    
    
    /**
     * findAll
     * Return all article in database
     * @return type
     */
    public function findAll(){
        return $this->repo->findAllDesc();
    }
    
    
    
    /**
     * findPublic
     * Return only public article (published and with past publication date)
     * @return type
     */
    public function findPublic(){
        return $this->repo->findAllPublic();
    }
    
    
    
    /**
     * getOlderArticles
     * Get articles to display on a certain page
     * @param type $page
     * @return type
     */
    public function getOlderArticles($page = 1){
        $numberOfMainArticleToGet   = $this
            ->container
            ->getParameter('gore_blog.main_articles_to_show');
        
        $numberOfSmallArticleToGet  = $this
            ->container
            ->getParameter('gore_blog.small_articles_to_show');
        
        $firstResult = $numberOfMainArticleToGet + ($page - 1) * $numberOfSmallArticleToGet;
        return $this->repo->getArticles($firstResult, $numberOfSmallArticleToGet);
    }
    
    
    
    /**
     * getMainArticles
     * Get N (= param) last articles to display in full size on the main blog page
     * @return type
     */
    public function getMainArticles(){
        $numberOfMainArticleToGet = $this
            ->container
            ->getParameter('gore_blog.main_articles_to_show');
        
        return $this->repo->getLastArticles($numberOfMainArticleToGet);
    }
    
    
    
    /**
     * delete
     * Delete an article
     * @param \Gore\BlogBundle\Entity\Article $article
     * @return boolean
     */
    public function delete(Article $article){
        if ($article instanceof Article){
            $em = $this->doctrine->getManager();
            $em->remove($article);
            $em->flush();
            return true;
        }
        return false;
    }
    
    
    
    /**
     * fillPrepersistArticle
     * Fill default fields of an article like author
     * @param \Gore\BlogBundle\Entity\Article $article
     * @return \Gore\BlogBundle\Entity\Article
     */
    public function fillPrepersistArticle(Article $article){
        $article->setAuthor($this->container
                                 ->get('security.context')
                                 ->getToken()
                                 ->getUser()
        );
        return $article;
    }
    
    
    
    /**
     * getTagsCloudData
     * Get most used keyword in the blog and random them
     */
    public function getTagsCloudData(){
        $mostUsedKeywords = $this->doctrine
                                 ->getManager()
                                 ->getRepository('GoreBlogBundle:Keyword')
                                 ->getMostUsedKeywords(10);
        
        $tagsCloudParams = $this->container
                                ->getParameter('gore_blog.tags_cloud_params');
        
        $unit   = $tagsCloudParams['font_size_unit'];
        $min    = $tagsCloudParams['min_font_size'];
        $max    = $tagsCloudParams['max_font_size'];
        $delta  = abs($max - $min);
        
        // additionnal data
        $tags = array();
        $maxOccurences = null;
        foreach($mostUsedKeywords as $tag){
            // the first is the most used : it has the max size
            if ($maxOccurences === null){
                $maxOccurences = $tag['compteur'];
                $fontSize = $max;
            } else {
                $fontSize = $min + floor($delta * $tag['compteur'] / $maxOccurences);
            }
            if ($fontSize < $min) $fontSize = $min;
            if ($fontSize > $max) $fontSize = $max;
            
            $tag['fontSize'] = $fontSize . $unit;
            $tags[] = $tag;
        }
        
        return $tags;
    }
    
    
    
    /**
     * getCategoriesData
     * Return all categories of the blog
     * @return type
     */
    public function getCategoriesData(){
        $categories = $this->doctrine
                           ->getManager()
                           ->getRepository('GoreBlogBundle:Keyword')
                           ->getAllCategories();
        return $categories;
    }
    
    
    
    /**
     * getPreviousArticleFromGivenOne
     * Get the article just before the given one (chronologically)
     * @param \Gore\BlogBundle\Entity\Article $article
     * @return type
     */
    public function getPreviousArticleFromGivenOne(Article $article){
        return $this->repo->getPrevious($article);
    }
    
    
    
    /**
     * getNextArticleFromGivenOne
     * Get the article just after the given one (chronologically)
     * @param \Gore\BlogBundle\Entity\Article $article
     * @return type
     */
    public function getNextArticleFromGivenOne(Article $article){
        return $this->repo->getNext($article);
    }
    
    
    
    /**
     * getCommonData
     * Get data common to many pages on the blog. For example : 
     *      > the blog title
     *      > the pictures folder
     *      > sidebar data
     *      > etc...
     */
    public function getCommonData(){
        // Parameters
        $picturesFolder = $this->container
                               ->getParameter('gore_blog.pictures_folder');
        $blogTitle      = $this->container
                               ->getParameter('gore_blog.blog_title');
        $socialAccounts = $this->container
                               ->getParameter('gore_blog.social_networks_urls');
        
        // tags cloud data
        $tags = $this->container->get('gore_blog.blog_manager')->getTagsCloudData();
        
        // categories data
        $categories = $this->container->get('gore_blog.blog_manager')->getCategoriesData();
        
        $commonData = array(
            'blogTitle'         => $blogTitle,
            'picturesFolder'    => $picturesFolder,
            'tagsCloudTags'     => $tags,
            'socialAccounts'    => $socialAccounts,
            'categories'        => $categories
        );
        
        return $commonData;
    }

}

?>
