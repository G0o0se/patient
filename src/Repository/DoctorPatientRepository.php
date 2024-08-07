<?php

namespace App\Repository;

use App\Entity\DoctorPatient;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<DoctorPatient>
 */
class DoctorPatientRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DoctorPatient::class);
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

    public function getPatientByDoctor($doctor, $patient)
    {
        return $this->createQueryBuilder('p')
            ->where('p.doctor = :doctor')
            ->andWhere('p.patient = :patient')
            ->setParameter('doctor', $doctor)
            ->setParameter('patient', $patient)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
