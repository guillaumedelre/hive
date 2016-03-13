<?php

namespace AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="admin_default_index")
     */
    public function indexAction()
    {
        $data = array(
            'pageTitle' => 'Administration',
        );
        return $this->render('AdminBundle:Default:index.html.twig', $data);
    }
}
