<?php

namespace App\Repository;

use App\Entity\Service;
use Doctrine\ORM\Query;
use App\Data\SearchServiceData;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Service>
 *
 * @method Service|null find($id, $lockMode = null, $lockVersion = null)
 * @method Service|null findOneBy(array $criteria, array $orderBy = null)
 * @method Service[]    findAll()
 * @method Service[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ServiceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Service::class);
    }

    public function add(Service $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Service $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findSearch(SearchServiceData $search): array {

        $query = $this->createQueryBuilder('s');
        
        if(!empty($search->q) || $search->q !== null){
            $query = $query
            ->andWhere('s.name_service LIKE :q')
            ->setParameter('q', "%{$search->q}%");
        } 
        if(!empty($search->min) || $search->min !== null){
            $query = $query
            ->andWhere('s.price_service >= :min')
            ->setParameter('min', $search->min);
        } 
        if(!empty($search->max) || $search->max !== null){
            $query = $query
            ->andWhere('s.price_service <= :max')
            ->setParameter('max', $search->max);
        }
        if(!empty($search->countryService) || $search->countryService !== null){
            $query = $query
            ->andWhere('s.country_service = :country')
            ->setParameter('country', $search->countryService);
        } 


        return $query->getQuery()->getResult();
    }


    public function findAllWithPagination() : Query{
        return $this->createQueryBuilder('v')
        ->getQuery();
    }
//    /**
//     * @return Service[] Returns an array of Service objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Service
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
