<?php

namespace App\Repository;

use App\Data\SearchData;
use App\Entity\Location;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Location>
 *
 * @method Location|null find($id, $lockMode = null, $lockVersion = null)
 * @method Location|null findOneBy(array $criteria, array $orderBy = null)
 * @method Location[]    findAll()
 * @method Location[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LocationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Location::class);
    }

    public function add(Location $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Location $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }



    public function findSearch(SearchData $search): array {

        $query = $this->createQueryBuilder('s');
        
        if(!empty($search->q) || $search->q !== null){
            $query = $query
            ->andWhere('s.name_location LIKE :q')
            ->setParameter('q', "%{$search->q}%");
        } 
        if(!empty($search->min) || $search->min !== null){
            $query = $query
            ->andWhere('s.price_location >= :min')
            ->setParameter('min', $search->min);
        } 
        if(!empty($search->max) || $search->max !== null){
            $query = $query
            ->andWhere('s.price_location <= :max')
            ->setParameter('max', $search->max);
        }
        if(!empty($search->countryLocation) || $search->countryLocation !== null){
            $query = $query
            ->andWhere('s.country_location = :country')
            ->setParameter('country', $search->countryLocation);
        } 


        return $query->getQuery()->getResult();
    }

}
