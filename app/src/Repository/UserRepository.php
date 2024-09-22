<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<User>
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function getCardUsernames(): array
    {
        return $this->createQueryBuilder('u')
            ->select('u.username, u.id, SUBSTRING(u.bio, 1, 201) AS bio, u.avatar, u.name, u.lastname')
            ->where('u.visible = TRUE')
            ->orderBy('u.id', 'ASC')
            ->getQuery()
            ->getArrayResult();
    }

    public function getAllInformation($userId): array
    {
        return $this->createQueryBuilder('u')
            ->select('u.username, u.email, u.avatar, u.banner, u.visible, u.name, u.lastname, u.bio, u.phone, u.address, u.country, u.zipCode, u.city, u.website, u.job, u.createdAt, u.updatedAt, u.lastLogin')
            ->where('u.id = :userId')
            ->setParameter('userId', $userId)
            ->getQuery()
            ->getArrayResult();
    }

    public function getUserByUsername($username): array
    {
        return $this->createQueryBuilder('u')
            ->select('u.id')
            ->where('u.username = :username')
            ->setParameter('username', $username)
            ->getQuery()
            ->getArrayResult();
    }


    public function findBySearchQuery($query)
    {
        return $this->createQueryBuilder('u')
            ->select('u.username, u.id, SUBSTRING(u.bio, 1, 201) AS bio, u.avatar, u.name, u.lastname')
            ->where('LOWER(u.username) LIKE LOWER(:query) and u.visible = TRUE')
            ->setParameter('query', '%' . strtolower($query) . '%')
            ->orderBy('u.id', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
