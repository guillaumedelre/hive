<?php

namespace CoreBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticleType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('category', EntityType::class , array(
                'label'      => 'Sélectionner une catégorie',
                'class'      => 'CoreBundle\Entity\Category',
                'attr'       => array('class' => 'custom-select'),
            ))
            ->add('author', EntityType::class , array(
                'label'      => 'Sélectionner un utilisateur',
                'class'      => 'CoreBundle\Entity\User',
                'attr'       => array('class' => 'custom-select'),
            ))
            ->add('title', TextType::class, array('label' => 'Titre'))
            ->add('description', TextareaType::class, array(
                'label' => 'Description',
                'attr' => array('class' => "tinymce", 'rows'  => 10),
            ))
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'CoreBundle\Entity\Article'
        ));
    }
}
