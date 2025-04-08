<?php

namespace App\Repository;

use App\Entity\UserSubmission;
use App\Entity\UserSubmissionSector;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class UserSubmissionSectorRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserSubmissionSector::class);
    }

    public function deleteBySubmission(UserSubmission $submission): void
    {
        $qb = $this->createQueryBuilder('uss');
        $qb->delete()
            ->where('uss.userSubmission = :submission')
            ->setParameter('submission', $submission)
            ->getQuery()
            ->execute();
    }
}