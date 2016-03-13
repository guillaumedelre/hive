<?php

namespace AdminBundle\Controller;

use CoreBundle\Entity\Category;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class CategoryController extends Controller
{
    /**
     * @Route("/categories", name="admin_category_index")
     */
    public function indexAction(Request $request)
    {
        $limit = $request->query->get('limit') ? $request->query->get('limit') : 20;
        $offset = $request->query->get('offset') ? $request->query->get('offset') : 0;

        $entity = new Category();

        $this->get('core.form.handler.category')->buildForm($entity);

        if ('POST' === $request->getMethod()) {
            try {
                $this->get('core.form.handler.category')->process($request, $entity);
                $this->get('session')->getFlashBag()->add('success', 'The category has been saved.');
            } catch (\Exception $e) {
                $this->get('session')->getFlashBag()->add('danger', $e->getMessage());
            }

            return $this->redirectToRoute('admin_category_index');
        }

        $data = array(
            'currentPage' => $offset,
            'totalPages'  => ceil(count($this->get('core.repository.category')->findAll()) / $limit),
            "categories"  => $this->get('core.repository.category')->findBy([], ['updatedAt' => 'DESC']),
            "form"        => $this->get('core.form.handler.category')->getForm()->createView(),
            'pageTitle'   => 'Catégories',
        );

        return $this->render('AdminBundle:Category:index.html.twig', $data);
    }

    /**
     * @Route("/categories/{id}/edit", name="admin_category_edit")
     */
    public function editAction(Request $request, $id)
    {
        /** @var Category */
        $entity = $this->get('core.repository.category')->find($id);

        if (null !== $entity) {
            $this->get('core.form.handler.category')->buildForm($entity);

            if ('POST' === $request->getMethod()) {
                try {
                    $this->get('core.form.handler.category')->process($request, $entity);
                    $this->get('session')->getFlashBag()->add('success', 'The simulator has been saved.');

                    return $this->redirectToRoute('admin_category_index');
                } catch (\Exception $e) {
                    $this->get('session')->getFlashBag()->add('danger', $e->getMessage());

                    return $this->redirectToRoute('admin_category_edit', ['id' => $id]);
                }
            }
        } else {
            $this->get('session')->getFlashBag()->add('danger', "Category #$id not found");
            return $this->redirectToRoute('admin_category_index');
        }

        $data = array(
            'pageTitle'   => 'Catégories',
            "category" => $entity,
            "form" => $this->get('core.form.handler.category')->getForm()->createView(),
        );

        return $this->render('AdminBundle:Category:edit.html.twig', $data);
    }

    /**
     * @Route("/categories/{id}/delete", name="admin_category_delete")
     */
    public function deleteAction(Request $request, $id)
    {
        /** @var Category */
        $entity = $this->get('core.repository.category')->find($id);

        if (null !== $entity) {
            $this->getDoctrine()->getManager()->remove($entity);
            $this->getDoctrine()->getManager()->flush();
            $this->get('session')->getFlashBag()->add('info', 'The category has been deleted.');
        } else {
            $this->get('session')->getFlashBag()->add('danger', "Category #$id not found");
        }

        return $this->redirectToRoute('admin_category_index');
    }
}
