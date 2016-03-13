<?php

namespace CoreBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
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
            ->add('username')
            ->add('password')
            ->add('email')
            ->add('isActive')
            ->add('theme', ChoiceType::class, array(
                'label' => 'Choose a theme',
                'choices' => [
                    'Clair'  => 'light',
                    'Sombre' => 'inverse',
                ],
            ))
            ->add('firstname')
            ->add('lastname')
            ->add('phoneNumber')
            ->add('cellNumber')
            ->add('address')
            ->add('zipCode')
            ->add('city')
            ->add('avatar')
            ->add('hive', EntityType::class , array(
                'label'      => 'Choose a hive',
                'class'      => 'CoreBundle\Entity\Hive',
            ))
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
