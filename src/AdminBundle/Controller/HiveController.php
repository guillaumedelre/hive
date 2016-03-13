<?php

namespace AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class HiveController extends Controller
{
    /**
     * @Route("/hives", name="admin_hive_index")
     */
    public function indexAction()
    {
        $data = array(
            'pageTitle' => 'Hive',
        );
        return $this->render('AdminBundle:Hive:index.html.twig', $data);
    }
}
