<?php

namespace Orange\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class CoreController extends Controller
{
    /**
     * @Route("/default")
     */
    public function indexAction()
    {
        return $this->render('OrangeCoreBundle:Default:index.html.twig');
    }
}
