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
        
        return $this->render('MegaloSecurityBundle:Default:loginGo.html.twig');
    }
}
