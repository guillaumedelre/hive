<?php

namespace AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DocumentController extends Controller
{
    /**
     * @Route("/documents", name="admin_document_index")
     */
    public function indexAction()
    {
        $data = array(
            'pageTitle' => 'Documents',
        );
        return $this->render('AdminBundle:Document:index.html.twig', $data);
    }
}
