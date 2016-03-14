<?php

namespace AdminBundle\Controller;

use CoreBundle\Entity\Article;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class ArticleController extends Controller
{
    /**
     * @Route("/articles", name="admin_article_index")
     */
    public function indexAction(Request $request)
    {
        $limit = $request->query->get('limit') ? $request->query->get('limit') : 20;
        $offset = $request->query->get('offset') ? $request->query->get('offset') : 0;

        $entity = new Article();

        $this->get('core.form.handler.article')->buildForm($entity);

        if ('POST' === $request->getMethod()) {
            try {
                $this->get('core.form.handler.article')->process($request, $entity);
                $this->get('session')->getFlashBag()->add('success', 'l\'article a bien été enregistré.');
            } catch (\Exception $e) {
                $this->get('session')->getFlashBag()->add('danger', $e->getMessage());
            }

            return $this->redirectToRoute('admin_article_index');
        }

        $data = array(
            'currentPage' => $offset,
            'totalPages'  => ceil(count($this->get('core.repository.article')->findAll()) / $limit),
            "articles"  => $this->get('core.repository.article')->findBy([], ['updatedAt' => 'DESC']),
            "form"        => $this->get('core.form.handler.article')->getForm()->createView(),
            'pageTitle'   => 'Articles',
        );

        return $this->render('AdminBundle:Article:index.html.twig', $data);
    }

    /**
     * @Route("/articles/{id}/edit", name="admin_article_edit")
     */
    public function editAction(Request $request, $id)
    {
        /** @var Article $entity */
        $entity = $this->get('core.repository.article')->find($id);

        if (null !== $entity) {
            $this->get('core.form.handler.article')->buildForm($entity);

            if ('POST' === $request->getMethod()) {
                try {
                    $this->get('core.form.handler.article')->process($request, $entity);
                    $this->get('session')->getFlashBag()->add('success', 'l\'article a bien été enregistré.');

                    return $this->redirectToRoute('admin_article_index');
                } catch (\Exception $e) {
                    $this->get('session')->getFlashBag()->add('danger', $e->getMessage());

                    return $this->redirectToRoute('admin_article_edit', ['id' => $id]);
                }
            }
        } else {
            $this->get('session')->getFlashBag()->add('danger', "Article #$id not found");
            return $this->redirectToRoute('admin_article_index');
        }

        $data = array(
            'pageTitle'   => 'Articles',
            "article" => $entity,
            "form" => $this->get('core.form.handler.article')->getForm()->createView(),
        );

        return $this->render('AdminBundle:Article:edit.html.twig', $data);
    }

    /**
     * @Route("/articles/{id}/delete", name="admin_article_delete")
     */
    public function deleteAction(Request $request, $id)
    {
        /** @var Article $entity */
        $entity = $this->get('core.repository.article')->find($id);

        if (null !== $entity) {
            $this->getDoctrine()->getManager()->remove($entity);
            $this->getDoctrine()->getManager()->flush();
            $this->get('session')->getFlashBag()->add('info', 'l\'article a bien été supprimé.');
        } else {
            $this->get('session')->getFlashBag()->add('danger', "Article #$id not found");
        }

        return $this->redirectToRoute('admin_article_index');
    }
}
