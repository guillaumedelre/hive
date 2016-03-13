<?php

namespace AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class VoteController extends Controller
{
    /**
     * @Route("/votes", name="admin_vote_index")
     */
    public function indexAction()
    {
        $data = array(
            'pageTitle' => 'Sondages',
        );
        return $this->render('AdminBundle:Vote:index.html.twig', $data);
    }
}
