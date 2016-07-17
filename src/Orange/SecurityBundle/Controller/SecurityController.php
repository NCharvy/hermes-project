<?php

namespace Orange\SecurityBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\SecurityContext;

class SecurityController extends Controller
{
    public function loginAction(Request $req){
        if($this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')){
            return $this->redirectToRoute('/');
        }

        $authUtils = $this->get('security.authentication_utils');

        return $this->render('OrangeSecurityBundle:Security:login.html.twig', array(
            'last_username' => $authUtils->getLastUsername(),
            'error'			=> $authUtils->getLastAuthenticationError()
        ));
    }

    public function logoutAction(Request $req){
        $this->get('security.context')->setToken(null);
        $this->get('req')->getSession()->invalidate();

        return $this->redirectToRoute('/login');
    }
}
