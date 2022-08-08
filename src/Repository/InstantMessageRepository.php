<?php

namespace App\Repository;

use App\Entity\InstantMessage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<InstantMessage>
 *
 * @method InstantMessage|null find($id, $lockMode = null, $lockVersion = null)
 * @method InstantMessage|null findOneBy(array $criteria, array $orderBy = null)
 * @method InstantMessage[]    findAll()
 * @method InstantMessage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InstantMessageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, InstantMessage::class);
    }

    public function add(InstantMessage $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(InstantMessage $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return InstantMessage[] Returns an array of InstantMessage objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('i.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

   public function findOneBySomeField($value): ?InstantMessage
   {
       return $this->createQueryBuilder('s')
           ->andWhere('s.user_id = :val')
           ->setParameter('val', $value)
           ->getQuery()
           ->getOneOrNullResult()
       ;
   }
}
