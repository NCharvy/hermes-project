<?php

namespace Orange\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use ZipArchive;

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

    /**
     * @Route("/api/load_sub_families")
     */
    public function loadSubFamiliesAction(Request $request)
    {
        if($request->getMethod() == 'POST')
        {
            $id = json_decode($request->getContent());
            $em = $this->getDoctrine()->getManager();
            $subfam = [];
            //$scat = $em->getRepository('OrangeBudgetBundle:SousCategorie')->findBy(array("categorie" => $cat));

            $sfam = $em->createQueryBuilder()
                ->select('sf')
                ->from('OrangeHomeBundle:SousTypologie', 'sf')
                ->innerJoin('sf.typologie', 'f')
                ->where('f.id = ' . $id->idfam)
                ->getQuery()
                ->getArrayResult();

            return new Response(json_encode(array("data" => $sfam)));
        }
    }

    /**
     * @Route("/api/load_families")
     */
    public function loadFamiliesAction(Request $request)
    {
        if($request->getMethod() == 'POST')
        {
            $id = json_decode($request->getContent());
            $em = $this->getDoctrine()->getManager();

            $fam = $em->createQueryBuilder()
                ->select('f')
                ->from('OrangeHomeBundle:Typologie', 'f')
                ->innerJoin('f.classification', 'c')
                ->where('c.id = ' . $id->idthema)
                ->getQuery()
                ->getArrayResult();

            return new Response(json_encode(array("data" => $fam)));
        }
    }

    /**
     * @Route("/api/load_created_archive")
     */
    public function loadCreatedArchiveAction(Request $request)
    {
        if($request->getMethod() == 'POST')
        {
            $em = $this->getDoctrine()->getManager();
            $json = json_decode($request->getContent());

            $path = __DIR__ . '../../../../../web/uploads';
            $name = 'save.zip';

            $files = $em->getRepository('OrangeHomeBundle:Fichier')
                         ->createQueryBuilder('f')
                         ->select('f')
                         ->where('f.archivage = true')
                         ->getQuery()
                         ->getResult();

            $zip = new ZipArchive;
            $zip->open($path . '/zip/' . $name, ZipArchive::OVERWRITE);
            foreach($files as $f){
                $absolpath = $path . '/archives/' . $f->getType()->getRoute() . '/' . $f->getLien();
                $relatpath = 'archives/' . $f->getType()->getRoute() . '/' . $f->getLien();
                $zip->addFile($absolpath, $relatpath);
            }
            $zip->close();

            if($json->del == 1){
                foreach($files as $f){
                    unlink(__DIR__ . '/../../../../web/uploads/archives/' . $f->getType()->getRoute() . '/' . $f->getLien());
                    $em->remove($f);
                }
                $em->flush();
            }

            if($zip->open($path . '/zip/' . $name)){
                return new Response(json_encode(array("data" => $name)));
            }
            else{
                return new Response('Erreur !');
            }
        }
    }
}
