<?php

namespace CoreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('uid')
            ->add('password')
            ->add('firstname')
            ->add('lastname')
            ->add('phoneNumber')
            ->add('cellNumber')
            ->add('address')
            ->add('zipCode')
            ->add('city')
            ->add('avatar')
            ->add('createdAt', 'datetime')
            ->add('updatedAt', 'datetime')
            ->add('hive')
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'CoreBundle\Entity\User'
        ));
    }
}
