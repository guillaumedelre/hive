<?php

namespace AdminBundle\Controller;

use CoreBundle\Entity\Event;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class EventController extends Controller
{
    /**
     * @Route("/events", name="admin_event_index")
     */
    public function indexAction(Request $request)
    {
        $limit = $request->query->get('limit') ? $request->query->get('limit') : 20;
        $offset = $request->query->get('offset') ? $request->query->get('offset') : 0;

        $entity = new Event();

        $this->get('core.form.handler.event')->buildForm($entity);

        if ('POST' === $request->getMethod()) {
            try {
                $this->get('core.form.handler.event')->process($request, $entity);
                $this->get('session')->getFlashBag()->add('success', 'a b.');
            } catch (\Exception $e) {
                $this->get('session')->getFlashBag()->add('danger', $e->getMessage());
            }

            return $this->redirectToRoute('admin_event_index');
        }

        $data = array(
            'currentPage' => $offset,
            'totalPages'  => ceil(count($this->get('core.repository.event')->findAll()) / $limit),
            "events"  => $this->get('core.repository.event')->findBy([], ['updatedAt' => 'DESC']),
            "form"        => $this->get('core.form.handler.event')->getForm()->createView(),
            'pageTitle'   => 'Evénements',
        );

        return $this->render('AdminBundle:Event:index.html.twig', $data);
    }

    /**
     * @Route("/events/{id}/edit", name="admin_event_edit")
     */
    public function editAction(Request $request, $id)
    {
        /** @var Event $entity */
        $entity = $this->get('core.repository.event')->find($id);

        if (null !== $entity) {
            $this->get('core.form.handler.event')->buildForm($entity);

            if ('POST' === $request->getMethod()) {
                try {
                    $this->get('core.form.handler.event')->process($request, $entity);
                    $this->get('session')->getFlashBag()->add('success', 'L\'événement a bien été enregistré.');

                    return $this->redirectToRoute('admin_event_index');
                } catch (\Exception $e) {
                    $this->get('session')->getFlashBag()->add('danger', $e->getMessage());

                    return $this->redirectToRoute('admin_event_edit', ['id' => $id]);
                }
            }
        } else {
            $this->get('session')->getFlashBag()->add('danger', "L'événement #id est introuvable");
            return $this->redirectToRoute('admin_event_index');
        }

        $data = array(
            'pageTitle'   => 'Evénements',
            "event" => $entity,
            "form" => $this->get('core.form.handler.event')->getForm()->createView(),
        );

        return $this->render('AdminBundle:Event:edit.html.twig', $data);
    }

    /**
     * @Route("/events/{id}/delete", name="admin_event_delete")
     */
    public function deleteAction(Request $request, $id)
    {
        /** @var Event $entity */
        $entity = $this->get('core.repository.event')->find($id);

        if (null !== $entity) {
            $this->getDoctrine()->getManager()->remove($entity);
            $this->getDoctrine()->getManager()->flush();
            $this->get('session')->getFlashBag()->add('info', 'L\'événement a bien été supprimé.');
        } else {
            $this->get('session')->getFlashBag()->add('danger', "L'événement #id est introuvable");
        }

        return $this->redirectToRoute('admin_event_index');
    }
}
