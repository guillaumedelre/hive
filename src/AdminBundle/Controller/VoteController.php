<?php

namespace AdminBundle\Controller;

use CoreBundle\Entity\Repository\EventRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class VoteController extends Controller
{
    /**
     * @Route("/votes", name="admin_vote_index")
     */
    public function indexAction(Request $request)
    {
        $limit = $request->query->get('limit') ? $request->query->get('limit') : 20;
        $offset = $request->query->get('offset') ? $request->query->get('offset') : 0;

        $data = array(
            'currentPage' => $offset,
            'totalPages'  => ceil(count($this->get('core.repository.event')->findAll()) / $limit),
            "currentEvents"  => $this->get('core.repository.event')->getCurrentVoteEvents(),
            "finishedEvents"  => $this->get('core.repository.event')->getFinishedVoteEvents(),
            'pageTitle' => 'Sondages',
        );

        return $this->render('AdminBundle:Vote:index.html.twig', $data);
    }
}
