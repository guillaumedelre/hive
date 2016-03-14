<?php

namespace CoreBundle\Form;

use CoreBundle\Entity\Repository\VoteRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VoteType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('event', EntityType::class , array(
                'label'      => 'Sélectionner un événement',
                'class'      => 'CoreBundle\Entity\Event',
            ))
            ->add('approved', ChoiceType::class, array(
                'label' => 'Sélectionner un choix',
                'choices' => [
                    'Approuvé' => VoteRepository::CHOICE_APPROVED,
                    'Refusé' => VoteRepository::CHOICE_REFUSED,
                ],
            ))
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'CoreBundle\Entity\Vote'
        ));
    }
}
