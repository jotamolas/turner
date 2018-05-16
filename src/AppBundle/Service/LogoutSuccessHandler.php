<?php

namespace AppBundle\Service;

use Symfony\Component\Security\Http\Logout\LogoutSuccessHandlerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Router;
use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

class LogoutSuccessHandler implements LogoutSuccessHandlerInterface {

    protected $router;
    protected $security;
    protected $em;
    protected $container;
    protected $token_storage;

    public function __construct(Router $router, AuthorizationChecker $security, EntityManager $em, ContainerInterface $container, TokenStorage $token_storage) {
        
        $this->router = $router;
        $this->security = $security;
        $this->em = $em;
        $this->container = $container;
        $this->token_storage = $token_storage;
    }

    public function onLogoutSuccess(Request $request) {
        
        /**
         * en el logout cierro la sesion iniciada y redirijo al usuario al login
         */
        
        $agent = $this->token_storage->getToken()->getUser(); 
        $this->container->get('session.service')->close($agent->getActiveSession());        
        
        return  new RedirectResponse($this->router->generate('fos_user_security_login'));
         
    }

}
