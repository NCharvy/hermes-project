<?php

namespace Orange\BackBundle\Controller;

use Orange\HomeBundle\Entity\Classification;
use Orange\HomeBundle\Entity\Fichier;
use Orange\HomeBundle\Entity\SousTypologie;
use Orange\HomeBundle\Entity\Type;
use Orange\HomeBundle\Entity\Typologie;
use Orange\HomeBundle\Entity\Extension;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

use Orange\HomeBundle\Form\ClassificationType;
use Orange\HomeBundle\Form\ClassificationUpType;
use Orange\HomeBundle\Form\FichierType;
use Orange\HomeBundle\Form\FichierUpType;
use Orange\HomeBundle\Form\TypeType;
use Orange\HomeBundle\Form\TypologieType;
use Orange\HomeBundle\Form\SousTypologieType;

class BackController extends Controller
{

    /**
     * @Route("/back", name="_back")
     */
    public function indexAction()
    {
        $chkfiles = $this->get('orange_core.check_files');

        $em = $this->getDoctrine()->getManager();
        $files = $em->getRepository('OrangeHomeBundle:Fichier')
                    ->createQueryBuilder('f')
                    ->select('f')
                    ->where('f.archivage = false')
                    ->getQuery()
                    ->getResult();
        $archives = $em->getRepository('OrangeHomeBundle:Fichier')
                    ->createQueryBuilder('f')
                    ->select('f')
                    ->where('f.archivage = true')
                    ->getQuery()
                    ->getResult();

        $chkfiles->setFiles($files);
        $chkfiles->setArchives($archives);
        $size = $chkfiles->getWeightValidFiles();
        $arch = $chkfiles->getWeightArchives();
        $nbfiles = $chkfiles->showNumberFiles($em);
        $disk = $chkfiles->getWeightFreeSpace();

        return $this->render('OrangeBackBundle:Back:index.html.twig', array(
            'size' => $size, 
            'arch' => $arch,
            'disk' => $disk,
            'nbfiles' => $nbfiles
        ));
    }

    /**
     * @Route("/back/classification", name="_classification")
     * @Template()
     */
    public function viewClassificationAction()
    {
        $em = $this->getDoctrine()->getManager();
        $class = $em->getRepository('OrangeHomeBundle:Classification')->findAll();

        return array(
            'class' => $class
        );    }

    /**
     * @Route("/back/classification/create", name="_classification_create")
     * @Template()
     */
    public function createClassificationAction(Request $req)
    {
        /*if(!$this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')){
            throw new AccessDeniedException('Accès limité aux utilisateurs authentifiés.');
        }*/

        $class = new Classification;
        $form = $this->get('form.factory')->create(new ClassificationType(), $class);
        $typeAction = "Création";

        if($form->handleRequest($req)->isValid()){
            $em = $this->getDoctrine()->getManager();
            $route = str_replace(' ', '_', $class->getLibelle());
            $route = str_replace('\'', '_', $route);
            $route = htmlentities(strtolower($route));
            $route = preg_replace('/&(.)[^;]+;/', '$1', $route);
            $class->setRoute($route);
            $em->persist($class);
            $em->flush();

            $req->getSession()->getFlashBag()->add('notice', 'La société a bien été ajoutée.');

            return $this->redirectToRoute('_classification');
        }

        return array('form' => $form->createView(), 'libelle' => $typeAction);
    }

    /**
     * @Route("/back/classification/update/{id}", name="_classification_update")
     * @Template("OrangeBackBundle:Back:createClassification.html.twig")
     */
    public function updateClassificationAction(Request $req, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $class = $em->getRepository('OrangeHomeBundle:Classification')->find($id);
        $typeAction = "Modification";

        $form = $this->get('form.factory')->create(new ClassificationUpType(), $class);

        if($form->handleRequest($req)->isValid()){
            $em->persist($class);
            $em->flush();

            $req->getSession()->getFlashBag()->add('notice', 'La société a bien été modifiée.');

            return $this->redirectToRoute('_classification');
        }

        return array(
            'form' => $form->createView(), 
            'libelle' => $typeAction,
            'visuel' => $class->getVisuel()
            );
    }

    /**
     * @Route("/back/classification/delete/{id}", name="_classification_delete")
     */
    public function deleteClassificationAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $class = $em->getRepository('OrangeHomeBundle:Classification')->find($id);

        $em->remove($class);
        $em->flush();

        return $this->redirectToRoute('_classification');
    }

    /**
     * @Route("/back/typologie", name="_typologie")
     * @Template()
     */
    public function viewTypologieAction()
    {
        $em = $this->getDoctrine()->getManager();
        $typo = $em->getRepository('OrangeHomeBundle:Typologie')->findAll();

        return array(
            'typo' => $typo
        );    }

    /**
     * @Route("/back/typologie/create", name="_typologie_create")
     * @Template()
     */
    public function createTypologieAction(Request $req)
    {
        /*if(!$this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')){
            throw new AccessDeniedException('Accès limité aux utilisateurs authentifiés.');
        }*/

        $typo = new Typologie;
        $form = $this->get('form.factory')->create(new TypologieType(), $typo);
        $typeAction = "Création";

        if($form->handleRequest($req)->isValid()){
            $em = $this->getDoctrine()->getManager();
            $route = str_replace(' ', '_', $typo->getLibelle());
            $route = str_replace('\'', '_', $route);
            $route = htmlentities(strtolower($route));
            $route = preg_replace('/&(.)[^;]+;/', '$1', $route);
            $typo->setRoute($route);
            $em->persist($typo);
            $em->flush();

            $req->getSession()->getFlashBag()->add('notice', 'La société a bien été ajoutée.');

            return $this->redirectToRoute('_typologie');
        }

        return array('form' => $form->createView(), 'libelle' => $typeAction);
    }

    /**
     * @Route("/back/typologie/update/{id}", name="_typologie_update")
     * @Template("OrangeBackBundle:Back:createTypologie.html.twig")
     */
    public function updateTypologieAction(Request $req, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $typo = $em->getRepository('OrangeHomeBundle:Typologie')->find($id);
        $typeAction = "Modification";

        $form = $this->get('form.factory')->create(new TypologieType(), $typo);

        if($form->handleRequest($req)->isValid()){
            $em->persist($typo);
            $em->flush();

            $req->getSession()->getFlashBag()->add('notice', 'La société a bien été modifiée.');

            return $this->redirectToRoute('_typologie');
        }

        return array('form' => $form->createView(), 'libelle' => $typeAction);
    }

    /**
     * @Route("/back/typologie/delete/{id}", name="_typologie_delete")
     */
    public function deleteTypologieAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $typo = $em->getRepository('OrangeHomeBundle:Typologie')->find($id);

        $em->remove($typo);
        $em->flush();

        return $this->redirectToRoute('_typologie');
    }

    /**
     * @Route("/back/soustypologie", name="_soustypologie")
     * @Template()
     */
    public function viewSousTypologieAction()
    {
        $em = $this->getDoctrine()->getManager();
        $stypo = $em->getRepository('OrangeHomeBundle:SousTypologie')->findAll();

        return array(
            'stypo' => $stypo
        );    }

    /**
     * @Route("/back/soustypologie/create", name="_soustypologie_create")
     * @Template()
     */
    public function createSousTypologieAction(Request $req)
    {
        /*if(!$this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')){
            throw new AccessDeniedException('Accès limité aux utilisateurs authentifiés.');
        }*/

        $stypo = new SousTypologie;
        $form = $this->get('form.factory')->create(new SousTypologieType(), $stypo);
        $typeAction = "Création";

        if($form->handleRequest($req)->isValid()){
            $em = $this->getDoctrine()->getManager();
            $route = str_replace(' ', '_', $stypo->getLibelle());
            $route = str_replace('\'', '_', $route);
            $route = htmlentities(strtolower($route));
            $route = preg_replace('/&(.)[^;]+;/', '$1', $route);
            $stypo->setRoute($route);

            $em->persist($stypo);
            $em->flush();

            $req->getSession()->getFlashBag()->add('notice', 'La société a bien été ajoutée.');

            return $this->redirectToRoute('_soustypologie');
        }

        return array('form' => $form->createView(), 'libelle' => $typeAction);
    }

    /**
     * @Route("/back/soustypologie/update/{id}", name="_soustypologie_update")
     * @Template("OrangeBackBundle:Back:createSousTypologie.html.twig")
     */
    public function updateSousTypologieAction(Request $req, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $stypo = $em->getRepository('OrangeHomeBundle:SousTypologie')->find($id);
        $typeAction = "Modification";

        $form = $this->get('form.factory')->create(new SousTypologieType(), $stypo);

        if($form->handleRequest($req)->isValid()){
            $em->persist($stypo);
            $em->flush();

            $req->getSession()->getFlashBag()->add('notice', 'La société a bien été modifiée.');

            return $this->redirectToRoute('_soustypologie');
        }

        return array('form' => $form->createView(), 'libelle' => $typeAction);
    }

    /**
     * @Route("/back/soustypologie/delete/{id}", name="_soustypologie_delete")
     */
    public function deleteSousTypologieAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $stypo = $em->getRepository('OrangeHomeBundle:SousTypologie')->find($id);

        $em->remove($stypo);
        $em->flush();

        return $this->redirectToRoute('_soustypologie');
    }

    /**
     * @Route("/back/fichier", name="_fichier")
     * @Template()
     */
    public function viewFichierAction()
    {
        $em = $this->getDoctrine()->getManager();
        $file = $em->getRepository('OrangeHomeBundle:Fichier')
                    ->createQueryBuilder('f')
                    ->select('f')
                    ->where('f.archivage = false')
                    ->getQuery()
                    ->getResult();
        $chkfiles = $this->get('orange_core.check_files');

        foreach($file as $f){
            if($f->getDateFin() != null){
                $dfin = $f->getDateFin()->format('Y-m-d');
                $end = strtotime($dfin);
                if($end <= time()){
                    $chkfiles->moveFile($f, $em);
                }
            }
        }

        return array(
            'file' => $file
        );    }

    /**
     * @Route("/back/fichier/create", name="_fichier_create")
     * @Template()
     */
    public function createFichierAction(Request $req)
    {
        /*if(!$this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')){
            throw new AccessDeniedException('Accès limité aux utilisateurs authentifiés.');
        }*/

        $file = new Fichier;
        $em = $this->getDoctrine()->getManager();
        $form = $this->get('form.factory')->create(new FichierType(), $file);
        $fam = $em->getRepository('OrangeHomeBundle:Typologie')->findAll();
        $typeAction = "Création";

        if($form->handleRequest($req)->isValid()){
            $f = $req->request->get('fam');
            $family = $em->getRepository('OrangeHomeBundle:Typologie')->find($f);
            $sf = $req->request->get('sfam');
            $sfamily = $em->getRepository('OrangeHomeBundle:SousTypologie')->find($sf);

            if(isset($sfamily) && !empty($sfamily)){
                $file->setSousTypologie($sfamily);
            }
            if(isset($family) && !empty($family)){
                $file->setTypologie($family);
            }

            $file->setDate(new \DateTime());
            $file->setArchivage(false);
            $em->persist($file);
            $em->flush();

            $req->getSession()->getFlashBag()->add('notice', 'La société a bien été ajoutée.');

            return $this->redirectToRoute('_fichier');
        }

        return array('form' => $form->createView(), 'libelle' => $typeAction, 'fam' => $fam);
    }

    /**
     * @Route("/back/fichier/update/{id}", name="_fichier_update")
     * @Template("OrangeBackBundle:Back:createFichier.html.twig")
     */
    public function updateFichierAction(Request $req, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $file = $em->getRepository('OrangeHomeBundle:Fichier')->find($id);
        $fam = $em->getRepository('OrangeHomeBundle:Typologie')->findAll();
        $typeAction = "Modification";

        $form = $this->get('form.factory')->create(new FichierUpType(), $file);

        if($form->handleRequest($req)->isValid()){
            $f = $req->request->get('fam');
            $family = $em->getRepository('OrangeHomeBundle:Typologie')->find($f);
            $sf = $req->request->get('sfam');
            $sfamily = $em->getRepository('OrangeHomeBundle:SousTypologie')->find($sf);

            if(isset($sfamily) && !empty($sfamily)){
                $file->setSousTypologie($sfamily);
            }
            if(isset($family) && !empty($family)){
                $file->setTypologie($family);
            }

            $em->persist($file);
            $em->flush();

            $req->getSession()->getFlashBag()->add('notice', 'La société a bien été modifiée.');

            return $this->redirectToRoute('_fichier');
        }

        return array(
            'form' => $form->createView(),
            'libelle' => $typeAction,
            'fam' => $fam,
            'route' => $file->getType()->getRoute(),
            'lien' => $file->getLien(),
            'fileFamille' => $file->getTypologie(),
            'fileSfamille' => $file->getSousTypologie()
        );
    }

    /**
     * @Route("/back/fichier/delete/{id}", name="_fichier_delete")
     */
    public function deleteFichierAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $file = $em->getRepository('OrangeHomeBundle:Fichier')->find($id);

        $em->remove($file);
        $em->flush();

        return $this->redirectToRoute('_fichier');
    }

    /**
     * @Route("/back/type", name="_type")
     * @Template()
     */
    public function viewTypeAction()
    {
        $em = $this->getDoctrine()->getManager();
        $type = $em->getRepository('OrangeHomeBundle:Type')->findAll();

        return array(
            'type' => $type
        );    }

    /**
     * @Route("/back/type/create", name="_type_create")
     * @Template()
     */
    public function createTypeAction(Request $req)
    {
        /*if(!$this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')){
            throw new AccessDeniedException('Accès limité aux utilisateurs authentifiés.');
        }*/

        $type = new Type;
        $form = $this->get('form.factory')->create(new TypeType(), $type);
        $typeAction = "Création";

        if($form->handleRequest($req)->isValid()){
            $em = $this->getDoctrine()->getManager();
            $route = str_replace(' ', '_', $type->getLibelle());
            $route = str_replace('\'', '_', $route);
            $route = htmlentities(strtolower($route));
            $route = preg_replace('/&(.)[^;]+;/', '$1', $route);
            $type->setRoute($route);
            $em->persist($type);
            $em->flush();

            $path = __DIR__ . "/../../../../web/uploads/resources/";

            $req->getSession()->getFlashBag()->add('notice', 'La société a bien été ajoutée.');

            return $this->redirectToRoute('_type');
        }

        return array('form' => $form->createView(), 'libelle' => $typeAction);
    }

    /**
     * @Route("/back/type/update/{id}", name="_type_update")
     * @Template("OrangeBackBundle:Back:createType.html.twig")
     */
    public function updateTypeAction(Request $req, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $type = $em->getRepository('OrangeHomeBundle:Type')->find($id);
        $typeAction = "Modification";

        $form = $this->get('form.factory')->create(new TypeType(), $type);

        if($form->handleRequest($req)->isValid()){
            $em->persist($type);
            $em->flush();

            $req->getSession()->getFlashBag()->add('notice', 'La société a bien été modifiée.');

            return $this->redirectToRoute('_type');
        }

        return array('form' => $form->createView(), 'libelle' => $typeAction);
    }

    /**
     * @Route("/back/type/delete/{id}", name="_type_delete")
     */
    public function deleteTypeAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $type = $em->getRepository('OrangeHomeBundle:Type')->find($id);

        $em->remove($type);
        $em->flush();

        return $this->redirectToRoute('_type');
    }

    /**
     * @Route("/back/archive", name="_archive")
     * @Template("OrangeBackBundle:Back:releaseFichier.html.twig")
     */
    public function viewArchiveAction()
    {
        $pathzip = __DIR__ . '/../../../../web/uploads/zip';
        $dirzip = dir($pathzip);
        while(false !== ($entry = $dirzip->read())){
            if($entry != '..' && $entry != '.'){
                unlink($pathzip . '/' . $entry);
            }
        }
        $dirzip->close();

        $em = $this->getDoctrine()->getManager();
        $file = $em->getRepository('OrangeHomeBundle:Fichier')
                    ->createQueryBuilder('f')
                    ->select('f')
                    ->where('f.archivage = true')
                    ->getQuery()
                    ->getResult();

        return array(
            'file' => $file,
            'archivage' => 'Ok !'
        );    }

    /**
    * @Route("/back/fichier/archive/{id}", name="_fichier_archive")
    */
    public function archiveFichierAction($id){
        $em = $this->getDoctrine()->getManager();

        $f = $em->getRepository('OrangeHomeBundle:Fichier')->find($id);
        $chkfiles = $this->get('orange_core.check_files');
        $chkfiles->moveFile($f, $em);

        return $this->redirectToRoute('_fichier');
    }

    /**
     * @Route("/back/archive/release", name="_archive_release")
     */
    public function releaseArchiveAction(Request $req)
    {
        $em = $this->getDoctrine()->getManager();
        $dfiles = $req->request->get('file');
        $path = __DIR__ . '/../../../../web/uploads/archives';

        foreach($dfiles as $df){
            $file = $em->getRepository('OrangeHomeBundle:Fichier')->find($df);
            $em->remove($file);
            $filepath = $path . '/' . $file->getType()->getRoute() . '/' . $file->getLien();
            unlink($filepath);
        }
        $em->flush();

        return $this->redirectToRoute('_archive');
    }
}
