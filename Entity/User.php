<?php

namespace Gore\BlogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;

/**
 * User
 *
 * @ORM\Table(name="goreblog_users")
 * @ORM\Entity(repositoryClass="Gore\BlogBundle\Entity\UserRepository")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    protected $facebookId;
    
    protected $googleId;
    
    protected $twitterId;
    

    public function __construct()
    {
        parent::__construct();
    }
    
    
}

