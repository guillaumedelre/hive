<?php

namespace AppBundle\Controller;

use CoreBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class MeController extends Controller
{
    /**
     * @Route("/{hiveSlug}/me", name="app_me_index")
     */
    public function indexAction(Request $request, $hiveSlug)
    {
        /** @var User $me */
        $me = $this->get('core.service.me')->getUser();

        $this->get('core.form.handler.me')->buildForm($me);

        if ('POST' === $request->getMethod()) {
            try {
                $this->get('core.form.handler.me')->process($request, $me);
                $this->get('session')->getFlashBag()->add('success', 'Le profil a bien été mis à jour.');

                return $this->redirectToRoute('app_me_index', ['hiveSlug' => $hiveSlug]);
            } catch (\Exception $e) {
                $this->get('session')->getFlashBag()->add('danger', $e->getMessage());

                return $this->redirectToRoute('app_me_index', ['hiveSlug' => $hiveSlug]);
            }
        }

        $data = array(
            'pageTitle' => 'Mon profil',
            "me"        => $me,
            "form"      => $this->get('core.form.handler.me')->getForm()->createView(),
        );

        return $this->render('AppBundle:Me:index.html.twig', $data);
    }
}
