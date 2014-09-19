Configuring GoreBlogBundle Resource Owners
===========================================

## Introduction

Now you have a blog with articles, you may want visitors to leave some comments 
on it. GoreBlogBundle provides a basic authentication form based on a username, 
an email and a password... But today, who does not prefer to use it's Facebook 
or Google account ?

That's why we provide the **HWIOAuthBundle** inside the GoreBlogBundle.

We already added, into the User entity, the attributes for the following 
resource owners : 
* Facebook
* Twitter 
* Google
* Github (because I love Github)

So if you want to add others resource owners, you have to do it on your own 
be going on the [HWIOAuthBundle Github Page](https://github.com/hwi/HWIOAuthBundle/blob/master/Resources/doc/2-configuring_resource_owners.md)


## What we did for you 
In the GoreBlogBundle, we already create a UserProvider.@TODO


## Configuring resource owners

We won't explain here wha the HWIOAuthBundle already explained on its doc.
* Facebook : [Go Here !](https://github.com/hwi/HWIOAuthBundle/blob/master/Resources/doc/resource_owners/facebook.md)
* Twitter : [Go Here !](https://github.com/hwi/HWIOAuthBundle/blob/master/Resources/doc/resource_owners/twitter.md)
* Google : [Go Here !](https://github.com/hwi/HWIOAuthBundle/blob/master/Resources/doc/resource_owners/google.md)
* Github : [Go Here !](https://github.com/hwi/HWIOAuthBundle/blob/master/Resources/doc/resource_owners/github.md)

Globally, you have to register your application on each resource owner's website 
you want to use. For example you have to register your application on the 
Facebook developers website in order to obtain an application key to put 
into the *parameters.yml* file of your app.

Once you have your application key, you can add the given resource owner to the 
*security.yml* file of your app in order to tell him *Hey, now you can accept 
Facebook user thank to the HWIOAuthBundke !*.

Finally, juste update your view to add corresponding login buttons ! 

Easy :)