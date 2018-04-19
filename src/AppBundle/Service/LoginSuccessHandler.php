<?php

namespace AppBundle\Service;

use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Router;
use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\ContainerInterface;


class LoginSuccessHandler implements AuthenticationSuccessHandlerInterface {

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

    public function onAuthenticationSuccess(Request $request, TokenInterface $token) {
        
        
        /*
         * En el logueo debo ver si el agente tiene una sesion activa... si tiene una sesion activa se lo envia al manager en su posicion
         * Si no tiene una sesion activa lo envio a la seleccion de una posicion para iniar una sesion.
         */
        
       $agent = $this->token_storage->getToken()->getUser();  
       $session = $agent->getActiveSession();
       
       if($session){
           return  new RedirectResponse($this->router->generate('turn_manage',[
               'agent' => $agent->getId()               
           ]));
       }else{
           return  new RedirectResponse($this->router->generate('turn_manage_position_selection_noposition',[
               'agent' => $agent->getId()
           ]));
       }
       
       
    }

}
