<?php

namespace AdminBundle\Controller;

use CoreBundle\Entity\Hive;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class HiveController extends Controller
{
    /**
     * @Route("/hives", name="admin_hive_index")
     */
    public function indexAction(Request $request)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Vous n\'avez pas les droits suffisant pour accéder à cette page!');

        $limit = $request->query->get('limit') ? $request->query->get('limit') : 20;
        $offset = $request->query->get('offset') ? $request->query->get('offset') : 0;

        $entity = new Hive();

        $this->get('core.form.handler.hive')->buildForm($entity);

        if ('POST' === $request->getMethod()) {
            try {
                $this->get('core.form.handler.hive')->process($request, $entity);
                $this->get('session')->getFlashBag()->add('success', 'The hive has been saved.');
            } catch (\Exception $e) {
                $this->get('session')->getFlashBag()->add('danger', $e->getMessage());
            }

            return $this->redirectToRoute('admin_hive_index');
        }

        $data = array(
            'currentPage' => $offset,
            'totalPages'  => ceil(count($this->get('core.repository.hive')->findAll()) / $limit),
            "hives"  => $this->get('core.repository.hive')->findBy([], ['updatedAt' => 'DESC']),
            "form"        => $this->get('core.form.handler.hive')->getForm()->createView(),
            'pageTitle'   => 'Ruches',
        );

        return $this->render('AdminBundle:Hive:index.html.twig', $data);
    }

    /**
     * @Route("/hives/{id}/edit", name="admin_hive_edit")
     */
    public function editAction(Request $request, $id)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Vous n\'avez pas les droits suffisant pour accéder à cette page!');

        /** @var Hive $entity */
        $entity = $this->get('core.repository.hive')->find($id);

        if (null !== $entity) {
            $this->get('core.form.handler.hive')->buildForm($entity);

            if ('POST' === $request->getMethod()) {
                try {
                    $this->get('core.form.handler.hive')->process($request, $entity);
                    $this->get('session')->getFlashBag()->add('success', 'The hive has been saved.');

                    return $this->redirectToRoute('admin_hive_index');
                } catch (\Exception $e) {
                    $this->get('session')->getFlashBag()->add('danger', $e->getMessage());

                    return $this->redirectToRoute('admin_hive_edit', ['id' => $id]);
                }
            }
        } else {
            $this->get('session')->getFlashBag()->add('danger', "Hive #$id not found");
            return $this->redirectToRoute('admin_hive_index');
        }

        $data = array(
            'pageTitle'   => 'Ruches',
            "hive" => $entity,
            "form" => $this->get('core.form.handler.hive')->getForm()->createView(),
        );

        return $this->render('AdminBundle:Hive:edit.html.twig', $data);
    }

    /**
     * @Route("/hives/{id}/delete", name="admin_hive_delete")
     */
    public function deleteAction(Request $request, $id)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Vous n\'avez pas les droits suffisant pour accéder à cette page!');

        /** @var Hive $entity */
        $entity = $this->get('core.repository.hive')->find($id);

        if (null !== $entity) {
            $this->getDoctrine()->getManager()->remove($entity);
            $this->getDoctrine()->getManager()->flush();
            $this->get('session')->getFlashBag()->add('info', 'The hive has been deleted.');
        } else {
            $this->get('session')->getFlashBag()->add('danger', "Hive #$id not found");
        }

        return $this->redirectToRoute('admin_hive_index');
    }
}
