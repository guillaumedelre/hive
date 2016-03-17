<?php

namespace AppBundle\Controller;

use CoreBundle\Entity\AbstractEntity;
use CoreBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class EventController extends Controller
{
    /**
     * @Route("/{hiveSlug}/evenements", name="app_event_index")
     */
    public function indexAction(Request $request, $hiveSlug)
    {
        $limit = $request->query->get('limit', AbstractEntity::DEFAULT_LIMIT_APP);
        $page  = $request->query->get('offset', 0);

        /** @var User $me */
        $me = $this->get('core.service.me')->getUser();

        if ($hiveSlug !== $me->getHive()->getSlug()) {
            $this->get('session')->getFlashBag()->add('danger', "L'url demandée est inconnue.");

            return $this->redirectToRoute('app_default_index', ['hiveSlug' => $me->getHive()->getSlug()]);
        }

        $events = $this->get('core.repository.event')->getMonthEvents();

        $data = array(
            'currentPage'   => $page,
            'currentLimit'  => $limit,
            'pageTitle'     => 'Articles',
            'me'            => $this->get('core.service.me')->getUser(),
            'events'        => $events,
        );

        return $this->render('AppBundle:Event:index.html.twig', $data);
    }
}
