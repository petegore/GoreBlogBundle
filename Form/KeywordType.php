<?php

namespace Gore\BlogBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class KeywordType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text', array('attr' => array('class'         => 'form-control',
                                                        'placeholder'   => 'Enter your keyword here...')))
        ;
    }
    
    
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     * @deprecated Not used ; replaced by a getForm into the AdminController
     */
    public function buildFullForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text', array('attr' => array('class'         => 'form-control',
                                                        'placeholder'   => 'Enter your keyword here...')))
            ->add('category', 'checkbox', array('required' => false))
        ;
    }
    
    
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Gore\BlogBundle\Entity\Keyword'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'gore_blogbundle_keyword';
    }
}
