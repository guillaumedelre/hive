<?php

namespace AppBundle\Controller;

use CoreBundle\Entity\AbstractEntity;
use CoreBundle\Entity\User;
use CoreBundle\Entity\Vote;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class VoteController extends Controller
{
    /**
     * @Route("/{hiveSlug}/sondages", name="app_vote_index")
     */
    public function indexAction(Request $request, $hiveSlug)
    {
        return $this->redirectToRoute('app_vote_by_status', ['hiveSlug' => $hiveSlug, 'status' => $this->get('cocur_slugify')->slugify('en cours')]);
    }

    /**
     * @Route("/{hiveSlug}/sondages/{status}", name="app_vote_by_status")
     */
    public function byStatusAction(Request $request, $hiveSlug, $status)
    {
        $limit = $request->query->get('limit', AbstractEntity::DEFAULT_LIMIT_APP);
        $page  = $request->query->get('offset', 0);

        /** @var User $me */
        $me = $this->get('core.service.me')->getUser();

        if ($hiveSlug !== $me->getHive()->getSlug()) {
            $this->get('session')->getFlashBag()->add('danger', "L'url demandée est inconnue.");

            return $this->redirectToRoute('app_default_index', ['hiveSlug' => $me->getHive()->getSlug()]);
        }

        switch($status) {
            case $this->get('cocur_slugify')->slugify('en cours'):
                $events     = $this->get('core.repository.event')->getCurrentVoteEvents($page * $limit);
                $totalPages = ceil(count($this->get('core.repository.event')->getAllCurrentVoteEvents()) / AbstractEntity::DEFAULT_LIMIT_APP);
                break;
            case $this->get('cocur_slugify')->slugify('terminé'):
                $events = $this->get('core.repository.event')->getFinishedVoteEvents($page * $limit);
                $totalPages = ceil(count($this->get('core.repository.event')->getAllFinishedVoteEvents()) / AbstractEntity::DEFAULT_LIMIT_APP);
                break;
        }

        $data = array(
            'currentPage'   => $page,
            'currentLimit'  => $limit,
            'pageTitle'     => 'Sondages',
            'totalPages'    => $totalPages,
            'me'            => $this->get('core.service.me')->getUser(),
            'events'        => $events,
            'currentStatus' => $status,
        );

        return $this->render('AppBundle:Vote:status.html.twig', $data);
    }

    /**
     * @Route("/{hiveSlug}/sondages/{eventId}/participation", name="app_vote_contribution")
     */
    public function getContributionByEventAction(Request $request, $hiveSlug, $eventId)
    {
        $contributon = null;

        /** @var User $me */
        $me = $this->get('core.service.me')->getUser();

        if ($hiveSlug !== $me->getHive()->getSlug()) {
            $this->get('session')->getFlashBag()->add('danger', "L'url demandée est inconnue.");
        }

        $event = $this->get('core.repository.event')->find($eventId);

        if (null === $event) {
            $this->get('session')->getFlashBag()->add('danger', "Une erreur s'est produite pendant la récupération de la participation.");
        } else {
            $contributon = $this->get('core.repository.event')->getCurrentContributionByEvent($event, $me);
        }

        return new JsonResponse($contributon);
    }

    /**
     * @Route("/{hiveSlug}/sondages/{eventId}/formulaire", name="app_vote_user_contribute_form")
     */
    public function getUserContributeForm(Request $request, $hiveSlug, $eventId)
    {
        $data = [];

        /** @var User $me */
        $me = $this->get('core.service.me')->getUser();

        if ($hiveSlug !== $me->getHive()->getSlug()) {
            $this->get('session')->getFlashBag()->add('danger', "L'url demandée est inconnue.");
        }

        $event = $this->get('core.repository.event')->find($eventId);

        if (!$this->get('core.repository.event')->userHasContributed($event, $me)) {
            $entity = new Vote();
            $entity->setUser($me);
            $entity->setEvent($event);

            $this->get('core.form.handler.myvote')->buildForm($entity, $this->generateUrl('app_vote_user_contribute', ['hiveSlug' => $hiveSlug, 'eventId' => $eventId]));

            $data = ["form" => $this->get('core.form.handler.myvote')->getForm()->createView()];
        }

        return $this->render('AppBundle:Vote:contribute.html.twig', $data);
    }

    /**
     * @Route("/{hiveSlug}/sondages/{eventId}/a-voter", name="app_vote_user_contribute")
     */
    public function contributeAction(Request $request, $hiveSlug, $eventId)
    {
        /** @var User $me */
        $me = $this->get('core.service.me')->getUser();

        if ($hiveSlug !== $me->getHive()->getSlug()) {
            $this->get('session')->getFlashBag()->add('danger', "L'url demandée est inconnue.");
        }

        $event = $this->get('core.repository.event')->find($eventId);

        if ('POST' === $request->getMethod()) {
            $entity = new Vote();
            $entity->setUser($me);
            $entity->setEvent($event);

            $this->get('core.form.handler.myvote')->buildForm($entity);

            $this->get('core.form.handler.myvote')->process($request, $entity);
        }

        return $this->redirectToRoute('app_vote_by_status', ['hiveSlug' => $hiveSlug, 'status' => $this->get('cocur_slugify')->slugify('en cours')]);
    }

    /**
     * @Route("/{hiveSlug}/sondages/{eventId}/graph", name="app_vote_graph")
     */
    public function getGraphAction(Request $request, $hiveSlug, $eventId)
    {
        $data = [];

        /** @var User $me */
        $me = $this->get('core.service.me')->getUser();

        if ($hiveSlug !== $me->getHive()->getSlug()) {
            $this->get('session')->getFlashBag()->add('danger', "L'url demandée est inconnue.");
        }

        $event = $this->get('core.repository.event')->find($eventId);

        $data['division'] = $this->get('core.repository.event')->getDivision($event);

        return $this->render('AppBundle:Vote:graph.html.twig', $data);
    }
}
