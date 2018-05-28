<?php

namespace App\Repository;

use App\Entity\Post;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class PostRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Post::class);
    }

    /*
    public function findBySomething($value)
    {
        return $this->createQueryBuilder('p')
            ->where('p.something = :value')->setParameter('value', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    public function getNewestPost($amount) {
        return $this->createQueryBuilder('p')
            ->where('p.id >= :id')->setParameter("id", 0)
            ->orderBy("p.createTime", "DESC")
            ->setMaxResults($amount)
            ->getQuery()
            ->getResult();
    }

    public function getPostInCategory($categoryId, $page, $perPage = 20) {
        return $this->createQueryBuilder('p')
            ->innerJoin('p.categories', 'c')
            ->where('c.id = :categoryId')
            ->setParameter('categoryId', $categoryId)
            ->orderBy('p.createTime', 'DESC')
            ->setFirstResult(($page - 1) * $perPage)
            ->setMaxResults($perPage)
            ->getQuery()
            ->getResult();
    }

    public function countPostInCategory($categoryAlias) {
        return $this->createQueryBuilder('p')
            ->select('COUNT(p)')
            ->innerJoin('p.categories', 'c')
            ->where('c.alias = :categoryAlias')
            ->setParameter('categoryAlias', $categoryAlias)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function getAllNewsByPage($page, $perPage = 20) {
        return $this->createQueryBuilder('p')
            ->where('p.id > :id')
            ->setParameter('id', 0)
            ->setFirstResult(($page - 1) * $perPage)
            ->setMaxResults($perPage)
            ->orderBy('p.createTime', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function countAllNews() {
        return $this->createQueryBuilder('p')
            ->select('COUNT(p)')
            ->where('p.id > :id')
            ->setParameter('id', 0)
            ->getQuery()
            ->getSingleScalarResult();
    }
}
