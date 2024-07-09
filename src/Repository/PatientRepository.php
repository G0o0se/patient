<?php

namespace App\Repository;

use App\Entity\Patient;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Patient>
 */
class PatientRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Patient::class);
    }

    public function findAllPatientsByDoctor($user)
    {
        return $this->createQueryBuilder('p')
            ->where('p.doctor = :doctor')
            ->orderBy('p.id', 'DESC')
            ->setParameter('doctor', $user)
            ->getQuery()
            ->getResult();
    }
}
