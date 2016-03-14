<?php

namespace AdminBundle\Controller;

use CoreBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class MeController extends Controller
{
    /**
     * @Route("/me", name="admin_me_index")
     */
    public function indexAction(Request $request)
    {
        /** @var User $entity */
        $entity = $this->get('core.repository.user')->findOneByUsername($this->getUser()->getUsername());

        if (null !== $entity) {
            $this->get('core.form.handler.user')->buildForm($entity);

            if ('POST' === $request->getMethod()) {
                try {
                    $this->get('core.form.handler.user')->process($request, $entity);
                    $this->get('session')->getFlashBag()->add('success', 'The user has been saved.');

                    return $this->redirectToRoute('admin_me_index');
                } catch (\Exception $e) {
                    $this->get('session')->getFlashBag()->add('danger', $e->getMessage());

                    return $this->redirectToRoute('admin_me_index');
                }
            }
        } else {
            $this->get('session')->getFlashBag()->add('danger', "User #".$this->getUser()->getUsername()." not found");
            return $this->redirectToRoute('admin_default_index');
        }

        $data = array(
            'pageTitle' => 'Mon profil',
            "document" => $entity,
            "form" => $this->get('core.form.handler.document')->getForm()->createView(),
        );

        return $this->render('AdminBundle:Me:index.html.twig', $data);
    }
}
