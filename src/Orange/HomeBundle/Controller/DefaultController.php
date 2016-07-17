<?php

namespace Orange\HomeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/truc")
     */
    public function indexAction()
    {
        return $this->render('OrangeHomeBundle:Default:index.html.twig');
    }
}
