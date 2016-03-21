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

        $data = array(
            'currentPage'   => $page,
            'currentLimit'  => $limit,
            'pageTitle'     => 'Evénements',
            'me'            => $this->get('core.service.me')->getUser(),
        );

        return $this->render('AppBundle:Event:index.html.twig', $data);
    }

    /**
     * @Route("/{hiveSlug}/evenements/periode", name="app_event_period")
     */
    public function periodAction(Request $request, $hiveSlug)
    {
        $limit = $request->query->get('limit', AbstractEntity::DEFAULT_LIMIT_APP);
        $page  = $request->query->get('offset', 0);

        /** @var User $me */
        $me = $this->get('core.service.me')->getUser();

        if ($hiveSlug !== $me->getHive()->getSlug()) {
            $this->get('session')->getFlashBag()->add('danger', "L'url demandée est inconnue.");

            return $this->redirectToRoute('app_default_index', ['hiveSlug' => $me->getHive()->getSlug()]);
        }

        $data = array(
            'currentPage'   => $page,
            'currentLimit'  => $limit,
            'pageTitle'     => 'Evénements',
            'me'            => $this->get('core.service.me')->getUser(),
        );

        return $this->render('AppBundle:Event:index.html.twig', $data);
    }

    /**
     * @Route("/{hiveSlug}/evenements/{eventSlug}", name="app_event_read")
     */
    public function readAction(Request $request, $hiveSlug, $eventSlug)
    {
        /** @var User $me */
        $me = $this->get('core.service.me')->getUser();

        if ($hiveSlug !== $me->getHive()->getSlug()) {
            $this->get('session')->getFlashBag()->add('danger', "L'url demandée est inconnue.");

            return $this->redirectToRoute('app_default_index', ['hiveSlug' => $me->getHive()->getSlug()]);
        }

        $event = $this->get('core.repository.event')->findOneBySlug($eventSlug);

        if (null === $event) {
            $this->get('session')->getFlashBag()->add('danger', "L'url demandée est inconnue.");

            return $this->redirectToRoute('app_default_index', ['hiveSlug' => $me->getHive()->getSlug()]);
        }

        $data = array(
            'pageTitle'     => 'Evénements',
            'me'            => $this->get('core.service.me')->getUser(),
            'event'         => $event,
        );

        return $this->render('AppBundle:Event:read.html.twig', $data);
    }
}
