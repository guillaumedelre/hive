<?php

namespace AdminBundle\Controller;

use CoreBundle\Entity\AbstractEntity;
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
        $limit = $request->query->get('limit', AbstractEntity::DEFAULT_LIMIT_ADMIN);
        $offset = $request->query->get('offset', 0);

        $entity = new Hive();

        $this->get('core.form.handler.hive')->buildForm($entity);

        if ('POST' === $request->getMethod()) {
            try {
                $this->get('core.form.handler.hive')->process($request, $entity);
                $this->get('session')->getFlashBag()->add('success', 'La ruche a bien été enregistrée.');
            } catch (\Exception $e) {
                $this->get('session')->getFlashBag()->add('danger', $e->getMessage());
            }

            return $this->redirectToRoute('admin_hive_index');
        }

        $data = array(
            'currentPage' => $offset,
            'currentLimit' => $limit,
            'totalPages'  => ceil(count($this->get('core.repository.hive')->findAll()) / $limit),
            "hives"  => $this->get('core.repository.hive')->findBy([], ['updatedAt' => 'DESC'], $limit, $offset * $limit),
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
        /** @var Hive $entity */
        $entity = $this->get('core.repository.hive')->find($id);

        if (null !== $entity) {
            $this->get('core.form.handler.hive')->buildForm($entity);

            if ('POST' === $request->getMethod()) {
                try {
                    $this->get('core.form.handler.hive')->process($request, $entity);
                    $this->get('session')->getFlashBag()->add('success', 'La ruche a bien été enregistrée.');

                    return $this->redirectToRoute('admin_hive_index');
                } catch (\Exception $e) {
                    $this->get('session')->getFlashBag()->add('danger', $e->getMessage());

                    return $this->redirectToRoute('admin_hive_edit', ['id' => $id]);
                }
            }
        } else {
            $this->get('session')->getFlashBag()->add('danger', "La ruche #$id est introuvable");
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
        /** @var Hive $entity */
        $entity = $this->get('core.repository.hive')->find($id);

        if (null !== $entity) {
            $this->getDoctrine()->getManager()->remove($entity);
            $this->getDoctrine()->getManager()->flush();
            $this->get('session')->getFlashBag()->add('info', 'La ruche a bien été supprimée.');
        } else {
            $this->get('session')->getFlashBag()->add('danger', "La ruche #$id est introuvable");
        }

        return $this->redirectToRoute('admin_hive_index');
    }
}
