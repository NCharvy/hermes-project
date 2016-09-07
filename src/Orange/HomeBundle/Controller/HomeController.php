<?php

namespace Orange\HomeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

class HomeController extends Controller
{
    /**
     * @Route("/", name="_home")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $classifications = $em->getRepository("OrangeHomeBundle:Classification")->findAll();
        return $this->render('OrangeHomeBundle:Home:index.html.twig', array(
            'classifications' => $classifications
        ));
    }

    /**
     * @Route("/files/typo/{id}", name="_show_files")
     */
    public function displayFilesTypoAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $typo = $em->getRepository("OrangeHomeBundle:Typologie")->find($id);
        $files = $em->getRepository("OrangeHomeBundle:Fichier")->findBy(array('typologie' => $id));

        return $this->render('OrangeHomeBundle:Home:display_files_typo.html.twig', array(
            'typo' => $typo,
            'files' => $files
        ));
    }

    /**
     * @Route("/arbo/{route}", name="_home_flevel")
     */
    public function displayTypoAction($route)
    {
        $em = $this->getDoctrine()->getManager();
        $t = $em->getRepository("OrangeHomeBundle:Typologie")->findOneBy(array('route' => $route));
        $stypos = $em->getRepository("OrangeHomeBundle:SousTypologie")->findBy(array('typologie' => $t->getId()));
        return $this->render('OrangeHomeBundle:Home:display_typo.html.twig', array(
            't' => $t,
            'stypos' => $stypos
        ));
    }

    /**
     * @Route("/files/stypo/{id}", name="_show_sfiles")
     */
    public function displayFilesStypoAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $st = $em->getRepository("OrangeHomeBundle:SousTypologie")->find($id);
        $typo = $st->getTypologie();
        $files = $em->getRepository("OrangeHomeBundle:Fichier")->findBy(array('soustypologie' => $id));

        return $this->render('OrangeHomeBundle:Home:display_files_stypo.html.twig', array(
            'st'    => $st,
            'typo'  =>  $typo,
            'files' => $files
        ));
    }

    /**
     * @Route("/arbo/{libelle}/{route}", name="_home_slevel")
     */
    public function displaySousTypoAction($libelle, $route)
    {
        return $this->render('OrangeHomeBundle:Home:display_sous_typo.html.twig', array(
            // ...
        ));
    }

    /**
    * @Route("/post-search", name="_post_search")
    */
    public function postSearch(Request $req){
        $search = $req->request->get('search');
        $em = $this->getDoctrine()->getManager();

        $query = $em->createQuery("SELECT f, t FROM OrangeHomeBundle:Type t INNER JOIN t.fichiers f WHERE f.nom LIKE '%$search%'");
        $files = $query->getResult();

        $thematique = $em->getRepository('OrangeHomeBundle:Classification')->findAll();
        $famille = $em->getRepository('OrangeHomeBundle:Typologie')->findAll();
        $sousfamille = $em->getRepository('OrangeHomeBundle:SousTypologie')->findAll();

        return $this->render('OrangeHomeBundle:Home:search.html.twig', array(
            'files' => $files,
            'thema' => $thematique,
            'fam' => $famille,
            'sfam' => $sousfamille, 
            'search' => $search
        ));
    }
}
