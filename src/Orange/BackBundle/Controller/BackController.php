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
        $files = $em->getRepository('OrangeHomeBundle:Fichier')->findAll();

        $chkfiles->setFiles($files);
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

        $form = $this->get('form.factory')->create(new ClassificationType(), $class);

        if($form->handleRequest($req)->isValid()){
            $em->persist($class);
            $em->flush();

            $req->getSession()->getFlashBag()->add('notice', 'La société a bien été modifiée.');

            return $this->redirectToRoute('_classification');
        }

        return array('form' => $form->createView(), 'libelle' => $typeAction);
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
                $end = strtotime($f->getDateFin());
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
        $form = $this->get('form.factory')->create(new FichierType(), $file);
        $typeAction = "Création";

        if($form->handleRequest($req)->isValid()){
            $em = $this->getDoctrine()->getManager();
            $exts = $em->getRepository('OrangeHomeBundle:Extension')->findAll();

            $lien = $file->getLien();
            $ext = substr(strrchr($lien, '.'), 1);
            $newext = null;

            foreach($exts as $e){
                if($ext == $e->getNom()){
                    $newext = $e->getNom();
                    break;
                }
            }
            if($newext == null){
                $extension = new Extension;
                $extension->setNom($ext);
                $extension->setType($file->getType());

                $em->persist($extension);
                $em->flush();
            }

            $extFile = $em->getRepository('OrangeHomeBundle:Extension')->findOneBy(array('nom' => $ext));

            $file->setExtension($extFile);
            $file->setDate(new \DateTime());
            $file->setArchivage(false);
            $em->persist($file);
            $em->flush();

            $req->getSession()->getFlashBag()->add('notice', 'La société a bien été ajoutée.');

            return $this->redirectToRoute('_fichier');
        }

        return array('form' => $form->createView(), 'libelle' => $typeAction);
    }

    /**
     * @Route("/back/fichier/update/{id}", name="_fichier_update")
     * @Template("OrangeBackBundle:Back:createFichier.html.twig")
     */
    public function updateFichierAction(Request $req, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $file = $em->getRepository('OrangeHomeBundle:Fichier')->find($id);
        $typeAction = "Modification";

        $form = $this->get('form.factory')->create(new FichierUpType(), $file);

        if($form->handleRequest($req)->isValid()){
            $em->persist($file);
            $em->flush();

            $req->getSession()->getFlashBag()->add('notice', 'La société a bien été modifiée.');

            return $this->redirectToRoute('_fichier');
        }

        return array('form' => $form->createView(), 'libelle' => $typeAction);
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
     * @Template("OrangeBackBundle:Back:viewFichier.html.twig")
     */
    public function viewArchiveAction()
    {
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
}
