<?php

namespace App\Repository;

use App\Entity\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class CategoryRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Category::class);
    }

    /*
    public function findBySomething($value)
    {
        return $this->createQueryBuilder('c')
            ->where('c.something = :value')->setParameter('value', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    public function getCategory($alias) {
        return $this->createQueryBuilder('c')
            ->where('c.alias = :alias')->setParameter('alias', $alias)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function getEnrollmentType($type) {
        /**
         type: bachelor, master, china, international, faq
         */
        return $this->createQueryBuilder('c')
            ->where('c.alias = :alias')->setParameter('alias', $type)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
