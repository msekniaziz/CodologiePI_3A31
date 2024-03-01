<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<User>
 *
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

//    /**
//     * @return User[] Returns an array of User objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('u.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?User
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
public function save(User $entity, bool $flush = false): void
{
    $this->getEntityManager()->persist($entity);

    if ($flush) {
        $this->getEntityManager()->flush();
    }
}

public function remove(User $entity, bool $flush = false): void
{
    $this->getEntityManager()->remove($entity);

    if ($flush) {
        $this->getEntityManager()->flush();
    }
}

//    /**
//     * @return User[] Returns an array of User objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('u.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?User
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
public function findBySearchQuery($query)
{
    $qb = $this->createQueryBuilder('u')
        ->where('u.nom LIKE :query')
        ->orWhere('u.prenom LIKE :query')
        ->orWhere('u.adress LIKE :query')
        ->orWhere('u.cin LIKE :query')
        ->orWhere('u.email LIKE :query')
        ->setParameter('query', '%' . $query . '%');

    return $qb->getQuery()->getResult();
} 
public function findBySearchQueryR($query)
{
    $qb = $this->createQueryBuilder('u')
        ->where('u.nom LIKE :query')
        ->orWhere('u.prenom LIKE :query')
        ->orWhere('u.adress LIKE :query')
        ->orWhere('u.cin LIKE :query')
        ->orWhere('u.roles LIKE :ROLE_TRANSPORTEUR')
        ->orWhere('u.email LIKE :query')
        ->setParameter('query', '%' . $query . '%')
        ->setParameter('ROLE_TRANSPORTEUR', 'ROLE_TRANSPORTEUR');

    return $qb->getQuery()->getResult();
} 
public function findUserByEmail(string $email): ?User
{
    return $this->createQueryBuilder('u')
        ->andWhere('u.email = :email')
        ->setParameter('email', $email)
        ->getQuery()
        ->getOneOrNullResult();
}

public function findBySearchQuerya($nsc){
return $this->createQueryBuilder('user')
->where('user.email LIKE :email')
->setParameter('email', '%'.$nsc.'%')
->getQuery()
->getResult();
}


public function findOneByUsername(string $username): ?User
{
    return $this->createQueryBuilder('u')
        ->andWhere('u.nom = :nom')
        ->setParameter('nom', $username)
        ->getQuery()
        ->getOneOrNullResult();
}
}
