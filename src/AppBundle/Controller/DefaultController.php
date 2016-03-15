<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/{hiveSlug}", name="app_default_index")
     */
    public function indexAction($hiveSlug)
    {
        $data = array(
            'pageTitle' => 'Tableau de bord',
            'me'        => $this->get('core.service.me')->getUser(),
        );

        return $this->render('AppBundle:Default:index.html.twig', $data);
    }
}
