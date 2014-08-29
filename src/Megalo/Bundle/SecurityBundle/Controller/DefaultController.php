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
        $this->get('megalo_security')->checkIpValid('test2', $env);
        $this->get('megalo_security')->logChange('test2', "Connexion de l'utilisateur test2");
        
        return $this->render('MegaloSecurityBundle:Default:loginGo.html.twig');
    }
}
