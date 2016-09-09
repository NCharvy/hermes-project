<?php

namespace Orange\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

class CoreController extends Controller
{
    /**
     * @Route("/default")
     */
    public function indexAction()
    {
        return $this->render('OrangeCoreBundle:Default:index.html.twig');
    }


    /** API **/

    /**
     * @Route("/api/load_custom_results")
     */
    public function loadCustomResults(Request $req){
        //var_dump($req->getContent());
        $search = $req->request->get('engsearch');
        $thema = $req->request->get('thema');
        $fam = $req->request->get('fam');
        $sfam = $req->request->get('sfam');
        $type = $req->request->get('type');

        $em = $this->getDoctrine()->getManager();
        $searchService = $this->get('orange_core.search_engine');
        $query = $searchService->getCustomResults($search, $thema, $fam, $sfam, $type, $em);

        return new JsonResponse(array(
            'query' => $query
        ));
    }
}
