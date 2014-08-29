<?php

namespace Megalo\Bundle\SecurityBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('MegaloSecurityBundle:Default:index.html.twig');
    }
    
    public function loginGoAction()
    {
        $env = $this->container->get('kernel')->getEnvironment();
        $this->get('megalo_security')->checkIpValid('test4', $env);
        $this->get('megalo_security')->logChange('test2', "Connexion de l'utilisateur test2");
        
        return $this->render('MegaloSecurityBundle:Default:loginGo.html.twig');
    }
    
    public function attemptAction()
    {
        $autorise = $this->get('megalo_security')->attempt('loginTest');
        
        return $this->render('MegaloSecurityBundle:Default:attempt.html.twig', array('autorise' => $autorise));
    }
    
    public function loginFailAction()
    {
        $this->get('megalo_security')->loginFail('loginTest');
        
        return $this->render('MegaloSecurityBundle:Default:login_fail.html.twig');
    }
    
    public function loginSuccessAction()
    {
        $this->get('megalo_security')->loginSuccess('loginTest');
        
        return $this->render('MegaloSecurityBundle:Default:login_success.html.twig');
    }
}
