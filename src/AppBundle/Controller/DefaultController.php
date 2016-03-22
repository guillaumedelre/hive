<?php

namespace AppBundle\Controller;

use CoreBundle\Entity\AbstractEntity;
use CoreBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/{hiveSlug}", name="app_default_index")
     */
    public function indexAction($hiveSlug)
    {
        /** @var User $me */
        $me = $this->get('core.service.me')->getUser();

        $current  = $this->get('core.repository.event')->getCurrentEvents($me);
        $incoming = $this->get('core.repository.event')->getIncomingEvents($me);

        $data = array(
            'pageTitle' => 'Tableau de bord',
            'me'        => $this->get('core.service.me')->getUser(),
            'current'   => $current,
            'incoming'  => $incoming,
        );

        return $this->render('AppBundle:Default:index.html.twig', $data);
    }
}
