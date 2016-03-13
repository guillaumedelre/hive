<?php

namespace AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class MeController extends Controller
{
    /**
     * @Route("/me", name="admin_me_index")
     */
    public function indexAction()
    {
        $data = array(
            'pageTitle' => 'Mon profil',
        );
        return $this->render('AdminBundle:Me:index.html.twig', $data);
    }
}
