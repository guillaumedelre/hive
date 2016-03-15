<?php
/**
 * Created by PhpStorm.
 * User: guillaume
 * Date: 25/02/16
 * Time: 14:08
 */

namespace CoreBundle\Form\Handler;


use CoreBundle\Entity\Document;
use Symfony\Component\HttpFoundation\Request;

class DocumentHandler extends AbstractFormHandler
{
    /**
     * @param Request $request
     * @param Document $entity
     * @return bool
     */
    public function process(Request $request, $entity)
    {
        $this->form->handleRequest($request);

        $filename   = $entity->getFile()->getClientOriginalName();
        $username   = $this->container->get('security.token_storage')->getToken()->getUser()->getUsername();
        $user       = $this->container->get('core.repository.user')->findOneByUsername($username);

        $entity->getFile()->move($entity->getUploadDir(), $filename);
        $entity->setSize($entity->getFile()->getClientSize());
        $entity->setPath($entity->getUploadDir() .$filename);
        $entity->setUser($user);

        if ($this->form->isSubmitted() && $this->form->isValid()) {
            $this->em->persist($entity);
            $this->em->flush();

            return true;
        }

        return false;
    }
}