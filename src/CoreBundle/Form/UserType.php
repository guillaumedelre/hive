<?php

namespace CoreBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
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
            ->add('username', TextType::class, array('label' => 'Nom d\'utilisateur'))
            ->add('password', PasswordType::class, array('label' => 'Mot de passe'))
            ->add('email', TextType::class, array('label' => 'Email'))
            ->add('isActive', CheckboxType::class, array('label' => 'Activé'))
            ->add('theme', ChoiceType::class, array(
                'label' => 'Sélectionner un thème',
                'choices' => [
                    'Clair'  => 'light',
                    'Sombre' => 'inverse',
                ],
            ))
            ->add('firstname', TextType::class, array('label' => 'Prénom'))
            ->add('lastname', TextType::class, array('label' => 'Nom'))
            ->add('phoneNumber', TextType::class, array('label' => 'Téléphone fixe'))
            ->add('cellNumber', TextType::class, array('label' => 'Téléphone portable'))
            ->add('address', TextType::class, array('label' => 'Adresse'))
            ->add('zipCode', TextType::class, array('label' => 'Code postal'))
            ->add('city', TextType::class, array('label' => 'Ville'))
            ->add('avatar', TextType::class, array('label' => 'Avatar'))
            ->add('hive', EntityType::class , array(
                'label'      => 'Sélectionner une ruche',
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
