<?php

namespace CoreBundle\Form;

use CoreBundle\Entity\Repository\EventRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
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
                'choices' => [
                    'Evénement' => EventRepository::TYPE_EVENT,
                    'Sondage'  => EventRepository::TYPE_VOTE,
                ],
            ))
            ->add('title', TextType::class, array('label' => 'Titre'))
            ->add('description', TextareaType::class, array('label' => 'Description'))
            ->add('startAt', TextType::class, array(
                'attr'  => array('data-provide' => "datepicker"),
                'label' => 'Début'
            ))
            ->add('endAt', TextType::class, array(
                'attr'  => array('data-provide' => "datepicker"),
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
