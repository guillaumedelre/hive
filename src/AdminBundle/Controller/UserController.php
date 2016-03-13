<?php

namespace AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class UserController extends Controller
{
    /**
     * @Route("/users", name="admin_user_index")
     */
    public function indexAction()
    {
        $data = array(
            'pageTitle' => 'Utilisateurs',
        );
        return $this->render('AdminBundle:User:index.html.twig', $data);
    }
}
