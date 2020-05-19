<?php

namespace App\Repository;

use App\Entity\Entreprise;

use App\Entity\Formation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Entreprise|null find($id, $lockMode = null, $lockVersion = null)
 * @method Entreprise|null findOneBy(array $criteria, array $orderBy = null)
 * @method Entreprise[]    findAll()
 * @method Entreprise[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EntrepriseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Entreprise::class);
    }

     /**
      * @return Entreprise[] Returns an array of Entreprise objects
      */
    
    public function findBySearch($departement,$nom,$secteur_activite,$formation)
    {
       $departement=$departement;$nom=$nom;$secteur_activite=$secteur_activite;$formation=$formation;
       $qb= $this->createQueryBuilder('e')
         //->select('e.id','e.nom as nom','e.secteur_activite','e.code_postal')
             ->innerJoin('e.formation', 'f')
             //->addSelect('f.Tag as tag')
        

            ;
             if($departement!=null)
            {
                $qb->andWhere('e.departement= :valDepartement');
                $qb->setParameter('valDepartement', $departement);
            }
            if($nom!=null)
            {
                $qb->andWhere('e.nom LIKE :valNom');
                $qb->setParameter('valNom', '%'.$nom.'%');
            }
            if($secteur_activite!=null)
            {
                $qb->andWhere('e.secteur_activite LIKE :valSecteur');
                $qb->setParameter('valSecteur', '%'.$secteur_activite.'%');
            }
            if($formation!=null)
            {
                $qb->andWhere('f.Nom LIKE :valFormation');
                $qb->setParameter('valFormation', '%'.$formation.'%');
            }
            $qb->orderBy('e.nom', 'ASC');
            $qb->groupBy('e.id');
            $query = $qb->getQuery();
            return $query->execute();
        
    }
    

    /*
    public function findOneBySomeField($value): ?Entreprise
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
