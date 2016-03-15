<?php
/**
 * Created by PhpStorm.
 * User: gdelre
 * Date: 15/03/16
 * Time: 18:55
 */

namespace CoreBundle\Service;


use CoreBundle\Entity\Repository\UserRepository;
use CoreBundle\Entity\User;
use Symfony\Component\Routing\Router;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class MeService
{
    /** @var User */
    private $user;

    /** @var UserRepository */
    private $userRepository;

    /** @var TokenStorageInterface */
    private $tokenStorage;

    /** @var Router */
    private $router;

    /**
     * MeService constructor.
     * @param UserRepository $userRepository
     * @param TokenStorageInterface $tokenStorage
     * @param Router $router
     */
    public function __construct(UserRepository $userRepository, TokenStorageInterface $tokenStorage, Router $router)
    {
        $this->userRepository = $userRepository;
        $this->tokenStorage   = $tokenStorage;
        $this->router         = $router;

        $username = $this->tokenStorage->getToken()->getUser()->getUsername();
        $user = $this->userRepository->findOneByUsername($username);

        if (null === $user) {
            throw new \Exception('L\'utilisateur est invalide.');
        } else {
            $this->user = $user;
        }
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }
}