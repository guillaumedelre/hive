<?php

namespace AdminBundle\Controller;

use CoreBundle\Entity\AbstractEntity;
use CoreBundle\Entity\Document;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class DocumentController extends Controller
{
    /**
     * @Route("/documents", name="admin_document_index")
     */
    public function indexAction(Request $request)
    {
        $limit = $request->query->get('limit', AbstractEntity::DEFAULT_LIMIT_ADMIN);
        $offset = $request->query->get('offset', 0);

        $entity = new Document();

        $this->get('core.form.handler.document')->buildForm($entity);

        if ('POST' === $request->getMethod()) {
            try {
                $this->get('core.form.handler.document')->process($request, $entity);
                $this->get('session')->getFlashBag()->add('success', 'Le document a bien été enregistré.');
            } catch (\Exception $e) {
                $this->get('session')->getFlashBag()->add('danger', $e->getMessage());
            }

            return $this->redirectToRoute('admin_document_index');
        }

        $data = array(
            'currentPage' => $offset,
            'currentLimit' => $limit,
            'totalPages'  => ceil(count($this->get('core.repository.document')->findAll()) / $limit),
            "documents"  => $this->get('core.repository.document')->findBy([], ['updatedAt' => 'DESC'], $limit, $offset * $limit),
            "form"        => $this->get('core.form.handler.document')->getForm()->createView(),
            'pageTitle'   => 'Documents',
        );

        return $this->render('AdminBundle:Document:index.html.twig', $data);
    }

    /**
     * @Route("/documents/{id}/edit", name="admin_document_edit")
     */
    public function editAction(Request $request, $id)
    {
        /** @var Document $entity */
        $entity = $this->get('core.repository.document')->find($id);

        if (null !== $entity) {
            $this->get('core.form.handler.document')->buildForm($entity);

            if ('POST' === $request->getMethod()) {
                try {
                    $this->get('core.form.handler.document')->process($request, $entity);
                    $this->get('session')->getFlashBag()->add('success', 'Le document a bien été enregistré.');

                    return $this->redirectToRoute('admin_document_index');
                } catch (\Exception $e) {
                    $this->get('session')->getFlashBag()->add('danger', $e->getMessage());

                    return $this->redirectToRoute('admin_document_edit', ['id' => $id]);
                }
            }
        } else {
            $this->get('session')->getFlashBag()->add('danger', "Le document #id est introuvable");
            return $this->redirectToRoute('admin_document_index');
        }

        $data = array(
            'pageTitle'   => 'Documents',
            "document" => $entity,
            "form" => $this->get('core.form.handler.document')->getForm()->createView(),
        );

        return $this->render('AdminBundle:Document:edit.html.twig', $data);
    }

    /**
     * @Route("/documents/{id}/delete", name="admin_document_delete")
     */
    public function deleteAction(Request $request, $id)
    {
        /** @var Document $entity */
        $entity = $this->get('core.repository.document')->find($id);

        if (null !== $entity) {
            @unlink($entity->getPath());
            $this->getDoctrine()->getManager()->remove($entity);
            $this->getDoctrine()->getManager()->flush();
            $this->get('session')->getFlashBag()->add('info', 'Le document a bien été supprimé.');
        } else {
            $this->get('session')->getFlashBag()->add('danger', "Le document #id est introuvable");
        }

        return $this->redirectToRoute('admin_document_index');
    }
}
