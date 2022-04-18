<?php

namespace App\Repository;

use App\Entity\FruitNutrient;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method FruitNutrient|null find($id, $lockMode = null, $lockVersion = null)
 * @method FruitNutrient|null findOneBy(array $criteria, array $orderBy = null)
 * @method FruitNutrient[]    findAll()
 * @method FruitNutrient[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FruitNutrientRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FruitNutrient::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(FruitNutrient $entity, bool $flush = true): void
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
    public function remove(FruitNutrient $entity, bool $flush = true): void
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
