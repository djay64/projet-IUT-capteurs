<?php

namespace App\Repository;

use App\Entity\Releve;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Releve|null find($id, $lockMode = null, $lockVersion = null)
 * @method Releve|null findOneBy(array $criteria, array $orderBy = null)
 * @method Releve[]    findAll()
 * @method Releve[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReleveRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Releve::class);
    }

    /**
      * @return Releve[] Returns an array of Releve objects
     */
    
    public function findByPm10MoyJour()
    {
        //charger le gestionnaire d'entité
        $gestionnaireEntite = $this->getEntityManager();

        //récupérer la date du jour 
        $dateDuJour =  date("Y-m-d");
        //décrire la requete
        $requete = $gestionnaireEntite->createQuery('SELECT AVG(r.pm10) FROM App\Entity\Releve r WHERE r.dateHeure LIKE :dateDuJour GROUP BY r.dateHeure');
        $requete->setParameter('dateDuJour', $dateDuJour.'%'); // mettre $dateDuJour au lieu de $date
        //exécuter la requete et retourner les résultats
        return $requete->execute();

    }

    
    /**
     * @return Releve[] Returns an array of Releve objects
     */
    
    
    public function findByPm25MoyJour()
    {
        //charger le gestionnaire d'entité
        $gestionnaireEntite = $this->getEntityManager();

        //récupérer la date du jour 
        $dateDuJour =  date("Y-m-d");
        //décrire la requete
        $requete = $gestionnaireEntite->createQuery('SELECT AVG(r.pm25) FROM App\Entity\Releve r WHERE r.dateHeure LIKE :dateDuJour GROUP BY r.dateHeure');
        $requete->setParameter('dateDuJour', $dateDuJour.'%'); // mettre $dateDuJour au lieu de $date
        //exécuter la requete et retourner les résultats
        return $requete->execute();
        
    }
    
    /**
      * @return Releve[] Returns an array of Releve objects
     */
    
    public function findByPm10($dateDebut, $dateFin, $capteurs)
    {
        //charger le gestionnaire d'entité
        $gestionnaireEntite = $this->getEntityManager();

        //décrire la requete
        if($dateDebut == $dateFin){
            $requete = $gestionnaireEntite->createQuery('SELECT AVG(r.pm10) FROM App\Entity\Releve r WHERE r.capteur IN (:capteurs) AND r.dateHeure LIKE :dateDebut GROUP BY r.dateHeure');
        }else{
            $requete = $gestionnaireEntite->createQuery('SELECT AVG(r.pm10) FROM App\Entity\Releve r WHERE r.capteur IN (:capteurs) AND r.dateHeure BETWEEN :dateDebut AND :dateFin GROUP BY r.dateHeure');
            $requete->setParameter('dateFin', $dateFin->format('Y-m-d').'%');
        }

        $requete->setParameter('capteurs', array_values($capteurs));
        $requete->setParameter('dateDebut', $dateDebut->format('Y-m-d').'%');
        
        //exécuter la requete et retourner les résultats
        return $requete->execute();

    }

    /**
     * @return Releve[] Returns an array of Releve objects
     */
    
    
    public function findByPm25($dateDebut, $dateFin, $capteurs)
    {
        //charger le gestionnaire d'entité
        $gestionnaireEntite = $this->getEntityManager();

        //décrire la requete
        if($dateDebut == $dateFin){
            $requete = $gestionnaireEntite->createQuery('SELECT AVG(r.pm25) FROM App\Entity\Releve r WHERE r.capteur IN (:capteurs) AND r.dateHeure LIKE :dateDebut GROUP BY r.dateHeure');
        }else{
            $requete = $gestionnaireEntite->createQuery('SELECT AVG(r.pm25) FROM App\Entity\Releve r WHERE r.capteur IN (:capteurs) AND r.dateHeure BETWEEN :dateDebut AND :dateFin GROUP BY r.dateHeure');
            $requete->setParameter('dateFin', $dateFin->format('Y-m-d').'%');
        }
        
        $requete->setParameter('capteurs', array_values($capteurs));
        $requete->setParameter('dateDebut', $dateDebut->format('Y-m-d').'%');

        //exécuter la requete et retourner les résultats
        return $requete->execute();
        
    }
    
    /**
     * @return Releve[] Returns an array of Releve objects
     */

    public function findByPm10Heure()
    {
        //charger le gestionnaire d'entité
        $gestionnaireEntite = $this->getEntityManager();

        //récupérer la date du jour 
        $dateDuJour =  date("Y-m-d");
        $heure =  date("H");
        //décrire la requete
        $requete = $gestionnaireEntite->createQuery('SELECT AVG(r.pm10) FROM App\Entity\Releve r WHERE r.dateHeure LIKE :dateDuJour AND r.heure LIKE :heure');
        $requete->setParameter('dateDuJour', $dateDuJour.'%'); 
        $requete->setParameter('heure', $heure.'%'); 
        //exécuter la requete et retourner les résultats
        return $requete->execute();

    }
    
    /**
     * @return Releve[] Returns an array of Releve objects
     */

    public function findByPm25Heure()
    {
        //charger le gestionnaire d'entité
        $gestionnaireEntite = $this->getEntityManager();

        //récupérer la date du jour 
        $dateDuJour =  date("Y-m-d");
        $heure =  date("H");
        //décrire la requete
        $requete = $gestionnaireEntite->createQuery('SELECT AVG(r.pm25) FROM App\Entity\Releve r WHERE r.dateHeure LIKE :dateDuJour AND r.heure LIKE :heure');
        $requete->setParameter('dateDuJour', $dateDuJour.'%'); 
        $requete->setParameter('heure', $heure.'%'); 
        //exécuter la requete et retourner les résultats
        return $requete->execute();

    }

    /*
    public function findOneBySomeField($value): ?Releve
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
