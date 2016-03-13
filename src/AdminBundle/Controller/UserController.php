<?php

namespace AdminBundle\Controller;

use CoreBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class UserController extends Controller
{
    /**
     * @Route("/users", name="admin_user_index")
     */
    public function indexAction(Request $request)
    {
        $limit = $request->query->get('limit') ? $request->query->get('limit') : 20;
        $offset = $request->query->get('offset') ? $request->query->get('offset') : 0;

        $entity = new User();

        $this->get('core.form.handler.user')->buildForm($entity, $this->getParameter('theme'));

        if ('POST' === $request->getMethod()) {
            try {
                $this->get('core.form.handler.user')->process($request, $entity);
                $this->get('session')->getFlashBag()->add('success', 'The user has been saved.');
            } catch (\Exception $e) {
                $this->get('session')->getFlashBag()->add('danger', $e->getMessage());
            }

            return $this->redirectToRoute('admin_user_index');
        }

        $data = array(
            'currentPage' => $offset,
            'totalPages'  => ceil(count($this->get('core.repository.user')->findAll()) / $limit),
            "users"  => $this->get('core.repository.user')->findBy([], ['updatedAt' => 'DESC']),
            "form"        => $this->get('core.form.handler.user')->getForm()->createView(),
            'pageTitle' => 'Utilisateurs',
        );

        return $this->render('AdminBundle:User:index.html.twig', $data);
    }


    /**
     * @Route("/users/{id}/edit", name="admin_user_edit")
     */
    public function editAction(Request $request, $id)
    {
        /** @var User */
        $entity = $this->get('core.repository.user')->find($id);

        if (null !== $entity) {
            $this->get('core.form.handler.user')->buildForm($entity);

            if ('POST' === $request->getMethod()) {
                try {
                    $this->get('core.form.handler.user')->process($request, $entity);
                    $this->get('session')->getFlashBag()->add('success', 'The user has been saved.');

                    return $this->redirectToRoute('admin_user_index');
                } catch (\Exception $e) {
                    $this->get('session')->getFlashBag()->add('danger', $e->getMessage());

                    return $this->redirectToRoute('admin_user_edit', ['id' => $id]);
                }
            }
        } else {
            $this->get('session')->getFlashBag()->add('danger', "User #$id not found");
            return $this->redirectToRoute('admin_user_index');
        }

        $data = array(
            'pageTitle'   => 'Catégories',
            "user" => $entity,
            "form" => $this->get('core.form.handler.user')->getForm()->createView(),
        );

        return $this->render('AdminBundle:User:edit.html.twig', $data);
    }

    /**
     * @Route("/users/{id}/delete", name="admin_user_delete")
     */
    public function deleteAction(Request $request, $id)
    {
        /** @var User */
        $entity = $this->get('core.repository.user')->find($id);

        if (null !== $entity) {
            $this->getDoctrine()->getManager()->remove($entity);
            $this->getDoctrine()->getManager()->flush();
            $this->get('session')->getFlashBag()->add('info', 'The user has been deleted.');
        } else {
            $this->get('session')->getFlashBag()->add('danger', "User #$id not found");
        }

        return $this->redirectToRoute('admin_user_index');
    }
}
