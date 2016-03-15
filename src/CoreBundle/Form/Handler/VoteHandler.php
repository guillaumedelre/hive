<?php
/**
 * Created by PhpStorm.
 * User: guillaume
 * Date: 25/02/16
 * Time: 14:08
 */

namespace CoreBundle\Form\Handler;


use CoreBundle\Entity\Vote;
use Symfony\Component\HttpFoundation\Request;

class VoteHandler extends AbstractFormHandler
{
    /**
     * @param Request $request
     * @param Vote $entity
     * @return bool
     */
    public function process(Request $request, $entity)
    {
        $username = $this->container->get('security.token_storage')->getToken()->getUser()->getUsername();

        $user = $this->container->get('core.repository.user')->findOneByUsername($username);

        $entity->setUser($user);

        return parent::process($request, $entity);
    }
}