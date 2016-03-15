<?php
/**
 * Created by PhpStorm.
 * User: guillaume
 * Date: 25/02/16
 * Time: 14:10
 */

namespace CoreBundle\Form\Handler;


use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\Request;

abstract class AbstractFormHandler
{
    /** @var EntityManager */
    protected $em;

    /** @var AbstractType */
    protected $formType;

    /** @var FormFactory */
    protected $formFactory;

    /** @var Form */
    protected $form;

    /** @var ContainerInterface */
    protected $container;

    /**
     * AbstractFormHandler constructor.
     * @param EntityManager $em
     * @param FormFactory $formFactory
     * @param ContainerInterface $container
     */
    public function __construct(EntityManager $em, FormFactory $formFactory, ContainerInterface $container)
    {
        $this->em = $em;
        $this->formFactory = $formFactory;
        $this->container = $container;
    }

    /**
     * @param AbstractType $formType
     * @return AbstractFormHandler
     */
    public function setFormType($formType)
    {
        $this->formType = $formType;

        return $this;
    }

    /**
     * @param $entity
     * @return Form
     */
    public function buildForm($entity)
    {
        $this->form = $this->formFactory
            ->createBuilder($this->formType, $entity)
            ->add('save', SubmitType::class, array('label_format' => 'Save', 'attr' => ['class' => "btn btn-default"]))
            ->getForm();

        return $this->form;
    }

    /**
     * @return Form
     */
    public function getForm()
    {
        return $this->form;
    }

    /**
     * @param Request $request
     * @param $entity
     * @return bool
     */
    public function process(Request $request, $entity)
    {
        $this->form->handleRequest($request);

        if ($this->form->isSubmitted() && $this->form->isValid()) {
            $this->em->persist($entity);
            $this->em->flush();

            return true;
        }

        return false;
    }
}