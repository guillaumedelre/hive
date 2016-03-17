<?php
/**
 * Created by PhpStorm.
 * User: gdelre
 * Date: 15/03/16
 * Time: 18:18
 */

namespace AppBundle\Controller;


use CoreBundle\Entity\AbstractEntity;
use CoreBundle\Entity\Document;
use CoreBundle\Entity\Hive;
use CoreBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class DocumentController extends Controller
{
    /**
     * @Route("/{hiveSlug}/documents", name="app_document_index")
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

        $documents = $this->get('core.repository.document')->getByHive($me->getHive(), $page * $limit);

        $categories = $this->get('core.repository.category')->getAllByHive($me->getHive());

        $data = array(
            'currentPage' => $page,
            'currentLimit' => $limit,
            'pageTitle'   => 'Documents',
            'totalPages'  => ceil(count($this->get('core.repository.document')->getAllByHive($me->getHive())) / AbstractEntity::DEFAULT_LIMIT_APP),
            'me'          => $this->get('core.service.me')->getUser(),
            'documents'    => $documents,
            'categories'  => $categories,
        );

        return $this->render('AppBundle:Document:index.html.twig', $data);
    }

    /**
     * @Route("/{hiveSlug}/documents/categories/{categorySlug}", name="app_document_by_category")
     */
    public function byCategoryAction(Request $request, $hiveSlug, $categorySlug)
    {
        $limit = $request->query->get('limit', AbstractEntity::DEFAULT_LIMIT_APP);
        $page  = $request->query->get('offset', 0);

        /** @var User $me */
        $me = $this->get('core.service.me')->getUser();

        if ($hiveSlug !== $me->getHive()->getSlug()) {
            $this->get('session')->getFlashBag()->add('danger', "L'url demandée est inconnue.");

            return $this->redirectToRoute('app_default_index', ['hiveSlug' => $me->getHive()->getSlug()]);
        }

        $category = $this->get('core.repository.category')->findOneBySlug($categorySlug);

        if (null === $category) {
            $this->get('session')->getFlashBag()->add('danger', "L'url demandée est inconnue.");

            return $this->redirectToRoute('app_default_index', ['hiveSlug' => $me->getHive()->getSlug()]);
        }

        $documents = $this->get('core.repository.document')->getByHiveAndCategory($me->getHive(), $category, $page * $limit);

        $categories = $this->get('core.repository.category')->getAllByHive($me->getHive());

        $data = array(
            'currentPage' => $page,
            'currentLimit' => $limit,
            'pageTitle'       => 'Documents',
            'totalPages'      => ceil(count($this->get('core.repository.document')->getAllByHiveAndCategory($me->getHive(), $category)) / AbstractEntity::DEFAULT_LIMIT_APP),
            'me'              => $this->get('core.service.me')->getUser(),
            'documents'        => $documents,
            'categories'      => $categories,
            'currentCategory' => $category,
        );

        return $this->render('AppBundle:Document:bycategory.html.twig', $data);
    }

}