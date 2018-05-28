<?php

namespace App\Repository;

use App\Entity\Member;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class MemberRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Member::class);
    }

    /*
    public function findBySomething($value)
    {
        return $this->createQueryBuilder('m')
            ->where('m.something = :value')->setParameter('value', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    public function findAllMemberWithoutTeam($teamId) {
        $qb = $this->createQueryBuilder('m');

        if (is_array($teamId)) {
            foreach ($teamId as $id) {
                $qb->orWhere("m.team != :teamId".$id)
                    ->setParameter("teamId".$id, $id);
            }
        } else {
            $qb->where('m.team != :teamId')
            ->setParameter('teamId', $teamId);
        }


        return $qb
            ->orderBy("m.memberOrder", "ASC")
            ->getQuery()
            ->getResult();

    }
}
