<?php

namespace App\Repository;

use App\Entity\SaladNutrient;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SaladNutrient|null find($id, $lockMode = null, $lockVersion = null)
 * @method SaladNutrient|null findOneBy(array $criteria, array $orderBy = null)
 * @method SaladNutrient[]    findAll()
 * @method SaladNutrient[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SaladNutrientRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SaladNutrient::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(SaladNutrient $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(SaladNutrient $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    // /**
    //  * @return NutrientsFruits[] Returns an array of NutrientsFruits objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('n.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?NutrientsFruits
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
