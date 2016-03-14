<?php
/**
 * Created by PhpStorm.
 * User: guillaume
 * Date: 14/03/16
 * Time: 16:08
 */

namespace AdminBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class SecurityController extends Controller
{
    /**
     * @Route("/logout", name="admin_security_logout")
     */
    public function logoutAction(Request $request)
    {
//        $this->get('request')->getSession()->invalidate();
//        $this->get('security.token_storage')->setToken(null);
    }
}