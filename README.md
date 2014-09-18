GoreBlogBundle
==============


A Symfony2 blog bundle (work in progres)

This bundle is not complete : the blog misses a lot of functionalities, and many bug remain.

I put it in GitHub / Packagist in order to use & test it in parallele of the dev.
You can check the progress on my developper blog that I just initiated at the same time as the bundle : http://www.petegore.fr

Thanks for your comprehension :)



# Uses
GoreBlodBundle uses different existing bundles like : 
* FOSUserBundle : user management
* HWIOAuthBundle : connection with Twitter, Fb, etc...
* StofDoctrineExtensionsBundle : to user Doctrine extensions such as Sluggable
* StfalconTinymceBundle : to load TinyMCE as text editor for articles

All these bundles will be installed at the same time as the BlogBundle.


# Installation

## Requiring the main bundle
Add the following requirements to your composer.json :

```
# composer.json

"require": {
    ...
    "petegore/blog-bundle": "dev-master"
},
```

And then run the update composer command :
``` bash
$ php composer.phar update
```



## Enabling the bundles

``` php
# app/AppKernel.php

$bundles = array(
    ...
    /** Blog and associated bundles **/
    new Gore\BlogBundle\GoreBlogBundle(),
    new Stof\DoctrineExtensionsBundle\StofDoctrineExtensionsBundle(),
    new FOS\UserBundle\FOSUserBundle(),
    new Stfalcon\Bundle\TinymceBundle\StfalconTinymceBundle(),
);
```



## Creating the main route
Add main blog route and FOSUserBundle routes : 

``` yaml
# app/config/routing.yml

# ROUTING FOR BLOGBUNDLE
blog:
    resource: "@GoreBlogBundle/Resources/config/routing/routing.yml"
    prefix:   /

# ROUTING FOR FOSUSERBUNDLE
fos_user_security:
    resource: "@FOSUserBundle/Resources/config/routing/security.xml"

fos_user_profile:
    resource: "@FOSUserBundle/Resources/config/routing/profile.xml"
    prefix: /profile

fos_user_register:
    resource: "@FOSUserBundle/Resources/config/routing/registration.xml"
    prefix: /register

fos_user_resetting:
    resource: "@FOSUserBundle/Resources/config/routing/resetting.xml"
    prefix: /resetting

fos_user_change_password:
    resource: "@FOSUserBundle/Resources/config/routing/change_password.xml"
    prefix: /profile
    
logout:
    pattern: /logout
```



## Create the security.yml file
```
# app/config/security.yml

security:
    encoders:
        Symfony\Component\Security\Core\User\User: plaintext
        FOS\UserBundle\Model\UserInterface: sha512

    role_hierarchy:
        ROLE_ADMIN:       ROLE_USER
        ROLE_SUPER_ADMIN: [ROLE_ADMIN]

    providers:
        fos_userbundle:
            id: fos_user.user_provider.username
            
    firewalls:
        main:
            pattern: ^/
            form_login:
                provider: fos_userbundle
                csrf_provider: form.csrf_provider
            logout:       true
            anonymous:    true

    access_control:
        - { path: ^/login$, role: IS_AUTHENTICATED_ANONYMOUSLY }
        - { path: ^/register, role: IS_AUTHENTICATED_ANONYMOUSLY }
        - { path: ^/resetting, role: IS_AUTHENTICATED_ANONYMOUSLY }
        - { path: ^/admin/, role: ROLE_ADMIN }
```



## Create your own admin user
By running the commands (for example) : 
``` bash
$ php app/console fos:user:create admin admin@mywebsite.com admin
$ php app/console fos:user:promote admin ROLE_ADMIN
```



## Updating the database schema
Update your database schema bu running update

``` bash
$ php app/console doctrine:schema:update --force
```



## Updating the assets
Run assets:install in order to install the public resources : 

``` bash
$ php app/console assets:install web/
```



## Creating a pictures folder
Create a folder to store articles pictures into your web/ folder.
For example : "web/pictures"
Note : the article creation process will create subfolders based on year and month.



## Set the minimal configuration
You only have to define the pictures folder into the YAML config file : 

``` yaml
# app/config/config.yml

# Blog configuration
gore_blog:
    pictures_folder: pictures/
    # will be completer later

# FOSUserBundle
fos_user:
    db_driver: orm
    firewall_name: main
    user_class: Gore\BlogBundle\Entity\User
    
# Doctrine extensions bundle
stof_doctrine_extensions:
    default_locale: %locale%
    orm:
        default:
            sluggable: true

# Text editor and syntax highlighter
stfalcon_tinymce:
    tinymce_jquery: true
    selector: ".tinymce"
    language: %locale%
    external_plugins:
        sh4tinymce:
            url: "asset[bundles/goreblog/lib/tinymce-plugin/sh4tinymce/plugin.js]"
    theme:
        simple: ~
        advanced:
            plugins:
                - "advlist autolink lists link image charmap print preview hr anchor pagebreak"
                - "searchreplace wordcount visualblocks visualchars code fullscreen"
                - "insertdatetime media nonbreaking save table contextmenu directionality"
                - "emoticons template paste textcolor"
            toolbar1: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image"
            toolbar2: "print preview media | forecolor backcolor emoticons | stfalcon | example"
            
```



## Customizing the blog
Here is an example of the complete configuration you car put on the blog : 

``` yaml
gore_blog:
    pictures_folder:        pictures/
    blog_title:             Chroniques d'un devweb
    main_articles_to_show:  2
    small_articles_to_show: 3
    social_networks_urls:
        email: test@myblog.com
        twitter : http://twitter.com/mypseudoontwitter
        ### etc... many social networks are availables
```

@TOFINISH
