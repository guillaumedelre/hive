<?php

namespace AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class CategoryController extends Controller
{
    /**
     * @Route("/categories", name="admin_category_index")
     */
    public function indexAction()
    {
        $data = array(
            'pageTitle' => 'Catégories',
        );
        return $this->render('AdminBundle:Category:index.html.twig', $data);
    }
}
