<?php

 namespace Gore\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Session\Session;

use Symfony\Component\HttpFoundation\File\UploadedFile;

use Gore\BlogBundle\Entity\Article;
use Gore\BlogBundle\Form\ArticleType;
use Gore\BlogBundle\Entity\Keyword;
use Gore\BlogBundle\Form\KeywordType;
use Gore\BlogBundle\Entity\Picture;



class AdminController extends Controller
{
    
    public function indexAction()
    {
        return $this->redirect($this->generateUrl('gore_blog_admin_add_article'));
    }
    
    
    /**
     * addArticleAction
     * Display the article builder page
     */
    public function addArticleAction(){
        // We get the cateogries to avoid the creation if there is no category defined
        $keywordsRepo = $this->getDoctrine()
                             ->getManager()
                             ->getRepository('GoreBlogBundle:Keyword');
        
        $categories = $keywordsRepo->getAllKeywords(1);
        
        $article = new Article;
        $form = $this->createForm(new ArticleType($this->get('security.context')), $article);

        $request = $this->get('request');
        if ($request->getMethod() == 'POST') {
            $form->bind($request);
                
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                
                // Traitement des valeurs par défaut
                foreach ($article->getKeywords() as $keyword){
                    $keyword->setCategory(false);
                }
                
                // Traitement de l'image
                $picture = $this->createPictureFromFile($form['thumbnail']['file']->getData());
                $article->setThumbnail($picture);
                
                $em->persist($article);
                $em->flush();

                return $this->redirect($this->generateUrl('gore_blog_admin_manage_articles'));
            } else {
                $this->get('session')->getFlashbag()->add('error', 'Article not valid');
                print_r($form->getErrors());
            }
        }

        return $this->render('GoreBlogBundle:Admin:add-article.html.twig', array(
                                                                                'blogTitle'     => $this->container->getParameter('gore_blog.blog_title'),
                                                                                'categories'    => $categories,
                                                                                'form'          => $form->createView(),
                                                                            ));
    }
    
    
    
    /**
     * manageArticlesAction
     * Display the articles manager page
     */
    public function manageArticlesAction(){
        // Getting all articles
        $articlesRepo = $this->getDoctrine()
                             ->getManager()
                             ->getRepository('GoreBlogBundle:Article');
        
        $articles = $this->get('gore_blog.articles_manager')->findAll();
        
        return $this->render('GoreBlogBundle:Admin:articles-list.html.twig', array(
            'blogTitle'     => $this->container->getParameter('gore_blog.blog_title'),
            'articles'   => $articles
        ));
    }
    
    
    /**
     * deleteArticleAction
     * @param \Gore\BlogBundle\Entity\Article $article
     * @return type
     */
    public function deleteArticleAction(Article $article){
        if ($this->get('gore_blog.articles_manager')->delete($article)){
            $this->get('session')->getFlashBag()->add('notice', 'Article "' . $article->getTitle() . '" has been correctly deleted.');
        } else {
            $this->get('session')->getFlashBag()->add('error', 'Impossible to delete the article.');
        }
        return $this->redirect($this->generateUrl('gore_blog_admin_manage_articles'));
    }
    
    
    
    /**
     * editArticleAction
     * Display the article-form filled with the article
     * @param \Gore\BlogBundle\Entity\Article $article
     * @return type
     */
    public function editArticleAction(Article $article){
        $picturesFolder = $this->container->getParameter('gore_blog.pictures_folder');
        
        // We get the cateogries to avoid the creation if there is no category defined
        $keywordsRepo = $this->getDoctrine()
                             ->getManager()
                             ->getRepository('GoreBlogBundle:Keyword');
        
        $categories = $keywordsRepo->getAllKeywords(1);
        
        if ($article){
            $form = $this->createForm(new ArticleType, $article);
            $request = $this->get('request');
            
            if ($request->getMethod() == 'POST') {
                $form->bind($request);

                if ($form->isValid()) {
                    $em = $this->getDoctrine()->getManager();

                    // Traitement des valeurs par défaut
                    foreach ($article->getKeywords() as $keyword){
                        $keyword->setCategory(false);
                    }

                    $em->persist($article);
                    $em->flush();
                    
                    $this->get('session')->getFlashBag()->add('notice', 'Your article has been successfully modified.');
                    return $this->redirect($this->generateUrl('gore_blog_admin_manage_articles'));
                }
            }

            return $this->render('GoreBlogBundle:Admin:add-article.html.twig', array(
                'blogTitle'     => $this->container->getParameter('gore_blog.blog_title'),
                'editionMode'       => true,
                'picturesFolder'    => $picturesFolder,
                'article'           => $article,
                'categories'        => $categories,
                'form'              => $form->createView(),
            ));
        } else {
            // If article couldn't be loaded
            $this->get('session')->getFlashBag()->add('error', 'Sorry, impossible to load the article you want to edit.');
            return $this->redirect($this->generateUrl('gore_blog_admin_manage_articles'));
        }
    }
    
    
    
    /**
     * toggleArticleAction
     * Change the "published" attribute of the article
     * @param \Gore\BlogBundle\Entity\Article $article
     * @return type
     */
    public function toggleArticleAction(Article $article){
        if ($article){
            $em = $this->getDoctrine()->getManager();
            
            $newpub = ($article->getPublished() == true) ? false : true;
            $article->setPublished($newpub);
            
            $em->persist($article);
            $em->flush();

            $status = ($newpub == true) ? "ONLINE" : "OFFLINE";
            $this->get('session')->getFlashBag()->add('notice', 'The article "' . $article->getTitle() .'" has been successfully put ' . $status . '.');
            return $this->redirect($this->generateUrl('gore_blog_admin_manage_articles'));
        } else {
            // If article couldn't be loaded
            $this->get('session')->getFlashBag()->add('error', 'Sorry, impossible to toggle the article.');
        }
        return $this->redirect($this->generateUrl('gore_blog_admin_manage_articles'));
    }
    
    
    
    /**
     * manageKeywordsAction
     * Display the keywords manager page
     */
    public function manageKeywordsAction(){
        // Treating the form
        $keyword = new Keyword;
        $form = $this->createForm(new KeywordType(), $keyword)
                     ->add('category', 'checkbox', array('required' => false));
        $request = $this->get('request');
        if ($request->getMethod() == 'POST') {
            $form->bind($request);

            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($keyword);
                $em->flush();
                
                $this->get('session')->getFlashBag()->add('notice', 'Keyword "' . $keyword->getName() . '" has been added.');
                return $this->redirect($this->generateUrl('gore_blog_admin_manage_keywords'));
            } else {
                 $this->get('session')->getFlashBag()->add('notice', 'Keyword form is not valid.');
            }
        }
        
        // Getting all keywords
        $keywordsRepo = $this->getDoctrine()
                             ->getManager()
                             ->getRepository('GoreBlogBundle:Keyword');
        
        $keywords = $keywordsRepo->getAllKeywords();
        $categories = $keywordsRepo->getAllKeywords(1);
        
        return $this->render('GoreBlogBundle:Admin:keywords.html.twig', array(
            'blogTitle'     => $this->container->getParameter('gore_blog.blog_title'),
            'form'       => $form->createView(),
            'keywords'   => $keywords,
            'categories' => $categories
        ));
    }
    
    
    /**
     * deleteKeywordAction
     * Delete one keyword by its ID
     */
    public function deleteKeywordAction(Keyword $keyword){
        if ($keyword){
            $em = $this->getDoctrine()
                       ->getManager();
            $em->remove($keyword);
            $em->flush();
            
            $this->get('session')->getFlashBag()->add('notice', 'Keyword "' . $keyword->getName() . '" has been deleted.');
        } else {
            $this->get('session')->getFlashBag()->add('error', 'No keyword to delete.');
        }
        
        return $this->redirect($this->generateUrl('gore_blog_admin_manage_keywords'));
    }
    
    
    
    /**
     * createPictureFromFile
     * Move an uploaded file and create a picture thanks to it
     * @param \Symfony\Component\HttpFoundation\File\UploadedFile $file
     * @return \Gore\BlogBundle\Entity\Picture
     */
    private function createPictureFromFile(UploadedFile $file){
        // moving the uploaded file to the pictures folder
        $extension = $file->guessExtension();
        if ($extension === null) $extension = "jpg";
        
        // note : mime types are filtered into the validation.yml file
        $folder = $this->container->getParameter('gore_blog.pictures_folder');
        $dt = new \Datetime();
        $fileName = $dt->getTimestamp() . "." . $file->getClientOriginalExtension();
        
        $file->move(
            $this->container->getParameter('gore_blog.pictures_folder') . '/' . ($dt->format('Y')) .  '/' . ($dt->format('n')), 
            $fileName
        );
        
        // creating the picture
        $picture = new Picture();
        $picture->setUrl($fileName);    // the folder will be taken dynamically from parameter
        return $picture;
    }
}
