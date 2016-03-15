<?php
/**
 * Created by PhpStorm.
 * User: gdelre
 * Date: 15/03/16
 * Time: 18:18
 */

namespace AppBundle\Controller;


use CoreBundle\Entity\AbstractEntity;
use CoreBundle\Entity\Article;
use CoreBundle\Entity\Hive;
use CoreBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class ArticleController extends Controller
{
    /**
     * @Route("/{hiveSlug}/articles", name="app_article_index")
     */
    public function indexAction(Request $request, $hiveSlug)
    {
        $page = $request->query->get('offset') ? $request->query->get('offset') : 0;

        /** @var User $me */
        $me = $this->get('core.service.me')->getUser();

        if ($hiveSlug !== $me->getHive()->getSlug()) {
            $this->get('session')->getFlashBag()->add('danger', "L'url demandée est inconnu.");

            return $this->redirectToRoute('app_default_index');
        }

        $articles = $this->get('core.repository.article')->getByHive($me->getHive(), $page);

        $categories = $this->get('core.repository.category')->getAllByHive($me->getHive());

        $data = array(
            'currentPage' => $page,
            'pageTitle'   => 'Articles',
            'totalPages'  => ceil(count($this->get('core.repository.article')->getAllByHive($me->getHive())) / 5),
            'me'          => $this->get('core.service.me')->getUser(),
            'articles'    => $articles,
            'categories'  => $categories,
        );

        return $this->render('AppBundle:Article:index.html.twig', $data);
    }

    /**
     * @Route("/{hiveSlug}/articles/{articleSlug}", name="app_article_read")
     */
    public function readAction(Request $request, $hiveSlug, $articleSlug)
    {
        /** @var User $me */
        $me = $this->get('core.service.me')->getUser();

        if ($hiveSlug !== $me->getHive()->getSlug()) {
            $this->get('session')->getFlashBag()->add('danger', "L'url demandée est inconnu.");

            return $this->redirectToRoute('app_default_index');
        }

        /** @var Article $article */
        $article = $this->get('core.repository.article')->findOneBySlug($articleSlug);

        $categories = $this->get('core.repository.category')->getAllByHive($me->getHive());

        $data = array(
            'pageTitle'  => $article->getTitle(),
            'me'         => $this->get('core.service.me')->getUser(),
            'article'    => $article,
            'categories' => $categories,
        );

        return $this->render('AppBundle:Article:read.html.twig', $data);
    }

    /**
     * @Route("/{hiveSlug}/articles/categories/{categorySlug}", name="app_article_by_category")
     */
    public function byCategoryAction(Request $request, $hiveSlug, $categorySlug)
    {
        $page = $request->query->get('offset') ? $request->query->get('offset') : 0;

        /** @var User $me */
        $me = $this->get('core.service.me')->getUser();

        if ($hiveSlug !== $me->getHive()->getSlug()) {
            $this->get('session')->getFlashBag()->add('danger', "L'url demandée est inconnu.");

            return $this->redirectToRoute('app_default_index');
        }

        $category = $this->get('core.repository.category')->findOneBySlug($categorySlug);

        if (null === $category) {
            $this->get('session')->getFlashBag()->add('danger', "L'url demandée est inconnu.");

            return $this->redirectToRoute('app_default_index');
        }

        $articles = $this->get('core.repository.article')->getByHiveAndCategory($me->getHive(), $category, $page);

        $categories = $this->get('core.repository.category')->getAllByHive($me->getHive());

        $data = array(
            'currentPage'     => $page,
            'pageTitle'       => 'Articles',
            'totalPages'      => ceil(count($this->get('core.repository.article')->getAllByHiveAndCategory($me->getHive(), $category)) / AbstractEntity::DEFAULT_LIMIT_APP),
            'me'              => $this->get('core.service.me')->getUser(),
            'articles'        => $articles,
            'categories'      => $categories,
            'currentCategory' => $category,
        );

        return $this->render('AppBundle:Article:bycategory.html.twig', $data);
    }

}