<?php

namespace App\Repository;

use App\Entity\Candidature;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Candidature|null find($id, $lockMode = null, $lockVersion = null)
 * @method Candidature|null findOneBy(array $criteria, array $orderBy = null)
 * @method Candidature[]    findAll()
 * @method Candidature[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CandidatureRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Candidature::class);
    }

    /**
     * @return Candidature[] Returns an array of Candidature objects
    */
    
    public function findByFormationReponseAccept($value,$value2)
    {
        return $this->createQueryBuilder('c')
            ->select('COUNT(c.reponse) as Accept')
            ->where('c.reponse = 4')
            ->andWhere('c.formation = :val')
            ->andWhere('c.entreprise = :val2')
            ->setParameter('val', $value)
            ->setParameter('val2', $value2)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return Candidature[] Returns an array of Candidature objects
    */
    
    public function findByFormationRefuseApresEntrerien($value,$value2)
    {
        return $this->createQueryBuilder('c')
            ->select('COUNT(c.reponse) as refus')
            ->where('c.reponse = 2')
            ->andWhere('c.formation = :val')
            ->andWhere('c.entreprise = :val2')
            ->setParameter('val', $value)
            ->setParameter('val2', $value2)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return Candidature[] Returns an array of Candidature objects
    */
    
    public function findByFormationRefuseSansEntrerien($value,$value2)
    {
        return $this->createQueryBuilder('c')
            ->select('COUNT(c.reponse) as refus')
            ->where('c.reponse = 3')
            ->andWhere('c.formation = :val')
            ->andWhere('c.entreprise = :val2')
            ->setParameter('val', $value)
            ->setParameter('val2', $value2)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return Candidature[] Returns an array of Candidature objects
    */
    
    public function findByFormationReponseEnAttente($value,$value2)
    {
        return $this->createQueryBuilder('c')
            ->select('COUNT(c.reponse) as EnAttente')
            ->where('c.reponse = 0')
            ->andWhere('c.formation = :val')
            ->andWhere('c.entreprise = :val2')
            ->setParameter('val', $value)
            ->setParameter('val2', $value2)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return Candidature[] Returns an array of Candidature objects
    */
    
    public function findByFormationReponseEntetien($value,$value2)
    {
        return $this->createQueryBuilder('c')
            ->select('COUNT(c.reponse) as EnAttente')
            ->where('c.reponse = 1')
            ->andWhere('c.formation = :val')
            ->andWhere('c.entreprise = :val2')
            ->setParameter('val', $value)
            ->setParameter('val2', $value2)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return Candidature[] Returns an array of Candidature objects
    */
    
    public function findByFormationEtatEnCours($value,$value2)
    {
        return $this->createQueryBuilder('c')
            ->select('COUNT(c.etat) as EnCours')
            ->where('c.etat = 0')
            ->andWhere('c.formation = :val')
            ->andWhere('c.entreprise = :val2')
            ->setParameter('val', $value)
            ->setParameter('val2', $value2)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return Candidature[] Returns an array of Candidature objects
    */
    
    public function findByFormationLettre($value,$value2)
    {
        return $this->createQueryBuilder('c')
            ->select('COUNT(c.moyen) as lettre')
            ->where('c.moyen = 0')
            ->andWhere('c.formation = :val')
            ->andWhere('c.entreprise = :val2')
            ->setParameter('val', $value)
            ->setParameter('val2', $value2)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return Candidature[] Returns an array of Candidature objects
    */
    
    public function findByFormationEmail($value,$value2)
    {
        return $this->createQueryBuilder('c')
            ->select('COUNT(c.moyen) as email')
            ->where('c.moyen = 1')
            ->andWhere('c.formation = :val')
            ->andWhere('c.entreprise = :val2')
            ->setParameter('val', $value)
            ->setParameter('val2', $value2)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return Candidature[] Returns an array of Candidature objects
    */
    
    public function findByFormationTel($value,$value2)
    {
        return $this->createQueryBuilder('c')
            ->select('COUNT(c.moyen) as tel')
            ->where('c.moyen = 2')
            ->andWhere('c.formation = :val')
            ->andWhere('c.entreprise = :val2')
            ->setParameter('val', $value)
            ->setParameter('val2', $value2)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return Candidature[] Returns an array of Candidature objects
    */
    
    public function findByFormationPlace($value,$value2)
    {
        return $this->createQueryBuilder('c')
            ->select('COUNT(c.moyen) as place')
            ->where('c.moyen = 3')
            ->andWhere('c.formation = :val')
            ->andWhere('c.entreprise = :val2')
            ->setParameter('val', $value)
            ->setParameter('val2', $value2)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findByFormationCandidature($value,$value2)
    {
        return $this->createQueryBuilder('c')
            ->select('COUNT(c.id) as nb')
            ->andWhere('c.formation = :val')
            ->andWhere('c.entreprise = :val2')
            ->setParameter('val', $value)
            ->setParameter('val2', $value2)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findByFormationRefus($value,$value2)
    {
        return $this->createQueryBuilder('c')
            ->select('COUNT(c.reponse) as refus')
            ->where('c.reponse = 2')
            ->where('c.reponse = 3')
            ->andWhere('c.formation = :val')
            ->andWhere('c.entreprise = :val2')
            ->setParameter('val', $value)
            ->setParameter('val2', $value2)
            ->getQuery()
            ->getResult()
        ;
    }
    /*
    public function findOneBySomeField($value): ?Candidature
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
