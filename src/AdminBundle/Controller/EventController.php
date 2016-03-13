<?php

namespace AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class EventController extends Controller
{
    /**
     * @Route("/events", name="admin_event_index")
     */
    public function indexAction()
    {
        $data = array(
            'pageTitle' => 'Evénements',
        );
        return $this->render('AdminBundle:Event:index.html.twig', $data);
    }
}
