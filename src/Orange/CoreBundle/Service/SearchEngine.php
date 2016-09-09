<?php

namespace Orange\CoreBundle\Service;

use Doctrine\ORM\EntityManager;

class SearchEngine
{
	public function getCustomResults($search, $thema, $fam, $sfam, $type, EntityManager $em)
    {
        $add = null;
        $join = null;
        $query = [];
        if(!empty($sfam)){
            $join .= "JOIN f.soustypologie sf ";
            if(!empty($add)){
                $add .= " AND sf.id = $sfam";
            }
            else{
                $add .= "sf.id = $sfam";
            }
        }
        if(!empty($fam)){
            $join .= "JOIN f.typologie fa ";
            if(!empty($add)){
                $add .= " AND fa.id = $fam";
            }
            else{
                $add .= "fa.id = $fam";
            }
        }
        if(!empty($thema)){
            if(!empty($fam)){
                $join .= "JOIN fa.classification cl ";
            }
            else{
                $join .= "JOIN f.typologie fa JOIN fa.classification cl ";
            }
            if(!empty($add)){
                $add .= " AND cl.id = $thema";
            }
            else{
                $add .= "cl.id = $thema";
            }
        }
        if(!empty($type)){
            $join .= "JOIN f.type t ";
            if(!empty($add)){
                $add .= " AND t.id = $type";
            }
            else{
                $add .= "t.id = $type";
            }
        }

        if(!empty($type)){
            $dql = "SELECT f, t FROM OrangeHomeBundle:Fichier f $join WHERE $add AND f.nom LIKE '%$search%'";
        }
        else{
            if(empty($thema) && empty($fam) && empty($sfam))
            {
                $add .= " f.nom LIKE '%$search%'";
                $dql = "SELECT ty, f, t FROM OrangeHomeBundle:Type ty JOIN ty.fichiers f JOIN f.type t $join WHERE $add";
            }
            else{
                $dql = "SELECT ty, f, t FROM OrangeHomeBundle:Type ty JOIN ty.fichiers f JOIN f.type t $join WHERE $add AND f.nom LIKE '%$search%'";
            }
        }
        $file = $em->createQuery($dql);
        $query = $file->getArrayResult();
   
        return $query;
    }

}