<?php

namespace Gore\BlogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Article
 *
 * @ORM\Table(name="goreblog_articles")
 * @ORM\Entity(repositoryClass="Gore\BlogBundle\Entity\ArticleRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Article
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text")
     */
    private $content;

    /**
     * @var string
     * @Gedmo\Slug(fields={"date", "title"}, dateFormat="Ymd")
     * @ORM\Column(name="slug", type="string", length=255)
     */
    private $slug;
    
    /**
     * @ORM\ManyToOne(targetEntity="Gore\BlogBundle\Entity\Keyword")
     * @ORM\JoinColumn(nullable=false)
     */
    private $category;
    
    /**
     * @ORM\ManyToOne(targetEntity="Gore\BlogBundle\Entity\User")
     * @ORM\JoinColumn(name="author_id", nullable=false)
     */
    private $author;

    /**
     * @ORM\ManyToOne(targetEntity="Gore\BlogBundle\Entity\Picture", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $thumbnail;
    
    /**
     * @ORM\ManyToMany(targetEntity="Gore\BlogBundle\Entity\Keyword", inversedBy="articles")
     * @ORM\JoinTable(name="goreblog_articles_keywords",
     *      joinColumns={@ORM\JoinColumn(name="article_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="keyword_id", referencedColumnName="id")})
     */
    private $keywords;
    
    /**
     * REVERSED RELATION
     * @ORM\OneToMany(targetEntity="Gore\BlogBundle\Entity\Comment", mappedBy="article")
     */
    private $comments;
    
    /**
     * @var boolean
     *
     * @ORM\Column(name="published", type="boolean", nullable=true)
     */
    private $published;
    
    
    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     * @return Article
     */
    public function setDate($date)
    {
        $this->date = $date;
    
        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime 
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return Article
     */
    public function setTitle($title)
    {
        $this->title = $title;
    
        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set content
     *
     * @param string $content
     * @return Article
     */
    public function setContent($content)
    {
        $this->content = $content;
    
        return $this;
    }

    /**
     * Get content
     *
     * @return string 
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set slug
     *
     * @param string $slug
     * @return Article
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
    
        return $this;
    }

    /**
     * Get slug
     *
     * @return string 
     */
    public function getSlug()
    {
        return $this->slug;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->date = new \DateTime();
        $this->keywords = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Set category
     *
     * @param \Gore\BlogBundle\Entity\Keyword $category
     * @return Article
     */
    public function setCategory(\Gore\BlogBundle\Entity\Keyword $category)
    {
        $this->category = $category;
    
        return $this;
    }

    /**
     * Get category
     *
     * @return \Gore\BlogBundle\Entity\Keyword 
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Add keywords
     *
     * @param \Gore\BlogBundle\Entity\Keyword $keywords
     * @return Article
     */
    public function addKeyword(\Gore\BlogBundle\Entity\Keyword $keywords)
    {
        $this->keywords[] = $keywords;
    
        return $this;
    }

    /**
     * Remove keywords
     *
     * @param \Gore\BlogBundle\Entity\Keyword $keywords
     */
    public function removeKeyword(\Gore\BlogBundle\Entity\Keyword $keywords)
    {
        $this->keywords->removeElement($keywords);
    }

    /**
     * Get keywords
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getKeywords()
    {
        return $this->keywords;
    }

    /**
     * Set published
     *
     * @param boolean $published
     * @return Article
     */
    public function setPublished($published)
    {
        $this->published = $published;
    
        return $this;
    }

    /**
     * Get published
     *
     * @return boolean 
     */
    public function getPublished()
    {
        return $this->published;
    }

    /**
     * Set thumbnail
     *
     * @param \Gore\BlogBundle\Entity\Picture $thumbnail
     * @return Article
     */
    public function setThumbnail(\Gore\BlogBundle\Entity\Picture $thumbnail)
    {
        $this->thumbnail = $thumbnail;
    
        return $this;
    }

    /**
     * Get thumbnail
     *
     * @return \Gore\BlogBundle\Entity\Picture 
     */
    public function getThumbnail()
    {
        return $this->thumbnail;
    }

    /**
     * Set author
     *
     * @param \Gore\BlogBundle\Entity\User $author
     * @return Article
     */
    public function setAuthor(\Gore\BlogBundle\Entity\User $author)
    {
        $this->author = $author;
    
        return $this;
    }

    /**
     * Get author
     *
     * @return \Gore\BlogBundle\Entity\User 
     */
    public function getAuthor()
    {
        return $this->author;
    }
}