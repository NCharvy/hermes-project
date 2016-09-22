<?php

namespace Orange\CoreBundle\Service;

use Doctrine\ORM\EntityManager;

use Orange\HomeBundle\Entity\Fichier;

class CheckFiles
{
    private $files;
    private $archives;

    /**
    * Partie index du back-office
    */

    public function showNumberFiles(EntityManager $em){
        $nball = [];
        $total = null;
        $nbfiles = $em->getRepository('OrangeHomeBundle:Fichier')
                    ->createQueryBuilder('f')
                    ->select('COUNT(f)')
                    ->where('f.archivage = false')
                    ->getQuery()
                    ->getSingleScalarResult();
        $nball[] = array('type' => "Nombre de fichiers valides", 'nb' => $nbfiles);
        $total += $nbfiles;
        $nbarchives = $em->getRepository('OrangeHomeBundle:Fichier')
                         ->createQueryBuilder('f')
                         ->select('COUNT(f)')
                         ->where('f.archivage = true')
                         ->getQuery()
                         ->getSingleScalarResult();
        $nball[] = array('type' => "Nombre de fichiers archivés", 'nb' => $nbarchives);
        $total += $nbarchives;

        $nball[] = array('type' => "Total des fichiers", 'nb' => $total);

        return $nball;
    }

    public function getWeightValidFiles(){
        $wfiles = null;
        $file = $this->getFiles();
        $path = __DIR__ . '/../../../../web/uploads/resources';

        if($file != null){
            foreach($file as $f){
                $fpath = $path . '/' . $f->getType()->getRoute() . '/' . $f->getLien();
                $wfiles += filesize($fpath);
            }

            $mb_size = $wfiles / 1024;
            $real_size = round($mb_size, 2) . " Ko";
            if(floor($mb_size) > 1000){
                $mb_size = $wfiles / pow(1024, 2);
                $real_size = round($mb_size, 2) . " Mo";
            }
            else if(floor($mb_size) > 1000){
                $mb_size = $wfiles / pow(1024, 3);
                $real_size = round($mb_size, 2) . " Go";
            }

            return $real_size;
        }
        else{
            $size = 0;
            $real_size = $size . " Ko";

            return $real_size;
        }
    }

    public function getWeightArchives(){
        $warchives = null;
        $archive = $this->getArchives();
        $path = __DIR__ . '/../../../../web/uploads/archives';

        if($archive != null){
            foreach($archive as $a){
                $apath = $path . '/' . $a->getType()->getRoute() . '/' . $a->getLien();
                $warchives += filesize($apath);
            }

            $mb_size = $warchives / 1024;
            $real_size = round($mb_size, 2) . " Ko";
            if(floor($mb_size) > 1000){
                $mb_size = $warchives / pow(1024, 2);
                $real_size = round($mb_size, 2) . " Mo";
            }
            else if(floor($mb_size) > 1000){
                $mb_size = $warchives / pow(1024, 3);
                $real_size = round($mb_size, 2) . " Go";
            }

            return $real_size;
        }
        else{
            $size = 0;
            $real_size = $size . " Ko";

            return $real_size;
        }
    }

    public function getWeightFreeSpace(){
        $path = __DIR__ . '/../../../../';
        $free = disk_free_space($path) / pow(1024, 3);
        $total = disk_total_space($path) / pow(1024, 3);
        $spf = round($free, 2);
        $spt = round($total, 2);
        $disk = [];

        $diskfree = $spf . " Go";
        $disk[] = array('type' => "Disk free space", 'nb' => $diskfree);
        $disktotal = $spt . " Go";
        $disk[] = array('type' => "Disk total space", 'nb' => $disktotal);
        $percent = round((($spf * 100) / $spt), 2);
        $disk[] = array('type' => "Percentage disk", 'nb' => $percent);


        return $disk;
    }

    /**
    * Partie Fichier du back-office
    */

    public function moveFile(Fichier $file, EntityManager $em)
    {
        $file->setArchivage(true);

        $name = $file->getLien();

        $oldFile = __DIR__ . '/../../../../web/uploads/resources/' . $file->getType()->getRoute() . '/' . $name;
        $newDir = __DIR__ . '/../../../../web/uploads/archives/' . $file->getType()->getRoute();
        if(file_exists($newDir . '/' . $name)){
            $ch = explode(".", $name);
            $name = $ch[0] . " (copie)." . $ch[1];
            $file->setLien($name);
        }

        $newFile = $newDir . '/' . $name;
        rename($oldFile, $newFile);

        $em->persist($file);
        $em->flush();
    }

    /**
    * Partie extensions de fichiers
    */

    public function attributeExtension(Fichier $file, EntityManager $em){
        $exts = $em->getRepository('OrangeHomeBundle:Extension')->findAll();

        $lien = $file->getLien();
        $ext = substr(strrchr($lien, '.'), 1);

        foreach($exts as $e){
            if($ext == $e->getNom()){
                $newext = $e->getNom();
                break;
            }
        }
        if(!isset($newext)){
            $extension = new Extension;
            $extension->setNom($ext);
            $extension->setType($file->getType());

            $em->persist($extension);
            $em->flush();
        }

        $file->setExtension($extFile);

        $extFile = $em->getRepository('OrangeHomeBundle:Extension')->findOneBy(array('nom' => $ext));

        return $extFile;
    }


    /**
    * Getters et Setters
    */

    public function setFiles($f){
        $this->files = $f;
    }

    public function getFiles(){
        return $this->files;
    }

    public function setArchives($a){
        $this->archives = $a;
    }

    public function getArchives(){
        return $this->archives;
    }
}