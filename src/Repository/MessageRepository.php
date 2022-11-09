<?php

namespace App\Repository;

use App\Entity\User;
use App\Entity\Message;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Message>
 *
 * @method Message|null find($id, $lockMode = null, $lockVersion = null)
 * @method Message|null findOneBy(array $criteria, array $orderBy = null)
 * @method Message[]    findAll()
 * @method Message[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MessageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Message::class);
    }

    public function add(Message $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Message $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return Message[] Returns an array of Message objects
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

   public function findOneBySomeField($value): ?Message
   {
       return $this->createQueryBuilder('s')
           ->andWhere('s.user_id = :val')
           ->setParameter('val', $value)
           ->getQuery()
           ->getOneOrNullResult()
       ;
   }


   public function findMessageRecipient(User $user, $locationId)
    {

        $entityManager = $this->getEntityManager();

        $userId = $user->getId();

        $query = $entityManager->createQuery(
            "
            SELECT u.$user->getFirstName()
            FROM user u
            WHERE u.$userId IN (SELECT owner_id
            FROM location_book bl
            INNER JOIN location l ON bl.location_id = l.id
            WHERE bl.location_client_id = $userId)
            "
        )->setParameter('user', $user);

        // returns an array of Product objects
        return $query->getResult();

        /*$conn = $this->getEntityManager()->getConnection();

        $sql = '
            SELECT u.first_name
            FROM user u
            WHERE u.id IN (SELECT owner_location_id
            FROM location_book bl
            INNER JOIN location l ON bl.location_id = l.id
            WHERE bl.location_client_id = 2)
            ';
        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery(['user' => $user]);

        // returns an array of arrays (i.e. a raw data set)
        return $resultSet->fetchAllAssociative();*/
    }

}
