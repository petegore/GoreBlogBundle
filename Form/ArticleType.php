<?php

namespace Gore\BlogBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Security\Core\SecurityContext;

use Doctrine\ORM\EntityRepository;

use Gore\BlogBundle\Form\KeywordType;
use Gore\BlogBundle\Form\PictureType;

class ArticleType extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // creating the basic article form
        $builder
            ->add('date', 'datetime')
            ->add('title', 'text', array(
                'attr'   =>  array(
                    'class'   => 'form-control',
                    'placeholder' => 'Enter here the title of your article'
                    )
                )
            )
            ->add('content', 'textarea', array(
                'required' => false, 
                'attr'   =>  array(
                    'class'   => 'tinymce',
                    'data-theme' => 'advanced'
                    )
                )
            )
            ->add('category', 'entity', array(
                    'class'         => 'GoreBlogBundle:Keyword',
                    'query_builder' =>  function(EntityRepository $er){
                                            return $er->createQueryBuilder('k')
                                                      ->where('k.category = 1')
                                                      ->orderBy('k.name', 'ASC');
                                        },
                    'property'      => 'name',
                    'required'      => true,
                    'attr'          =>  array(
                        'class'   => 'form-control'
                    )
                )
            )
            ->add('thumbnail', new PictureType(), array(
                'required' => false
                )
            )
            ->add('keywords', 'collection', array(  
                'required'     => false,
                'type'         => new KeywordType(),
                'allow_add'    => true,
                'allow_delete' => true,
                'by_reference' => false
                )
            )
            ->add('published', 'choice', array(
                'choices' => array(
                    "1" => "Published - My article will be online", 
                    "0" => "Draft - My article will be offline for the moment"
                ),
                "attr" =>  array(
                    'class'   => 'form-control'
                    ) 
                )
            )
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Gore\BlogBundle\Entity\Article'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'gore_blogbundle_article';
    }
}
