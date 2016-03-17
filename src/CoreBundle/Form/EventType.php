<?php

namespace CoreBundle\Form;

use CoreBundle\Entity\Repository\EventRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EventType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('type', ChoiceType::class, array(
                'label' => 'Sélectionner un type',
                'attr'       => array('class' => 'custom-select'),
                'choices' => [
                    'Evénement' => EventRepository::TYPE_EVENT,
                    'Sondage'  => EventRepository::TYPE_VOTE,
                ],
            ))
            ->add('user', EntityType::class , array(
                'label'      => 'Sélectionner un utilisateur',
                'class'      => 'CoreBundle\Entity\User',
                'attr'       => array('class' => 'custom-select'),
            ))
            ->add('title', TextType::class, array('label' => 'Titre'))
            ->add('description', TextareaType::class, array('label' => 'Description'))
            ->add('startAt', DateType::class, array(
                'widget' => 'single_text',
                'format' => 'dd/MM/yyyy',
                'attr'   => array('data-provide' => "datepicker", 'data-date-format' => 'dd/mm/yyyy'),
                'label'  => 'Début'
            ))
            ->add('endAt', DateType::class, array(
                'widget' => 'single_text',
                'format' => 'dd/MM/yyyy',
                'attr'  => array('data-provide' => "datepicker", 'data-date-format' => 'dd/mm/yyyy'),
                'label' => 'Fin',
            ))
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'CoreBundle\Entity\Event'
        ));
    }
}
