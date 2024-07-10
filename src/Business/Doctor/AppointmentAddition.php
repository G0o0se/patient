<?php
namespace App\Business\Doctor;

use App\Entity\Appointment;
use App\Entity\User;
use App\Repository\DoctorPatientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;

class AppointmentAddition
{

    private EntityManagerInterface $entityManager;

    private Security $security;

    private DoctorPatientRepository $doctorPatientRepository;

    public function __construct(
        EntityManagerInterface $entityManager,
        Security $security,
        DoctorPatientRepository $doctorPatientRepository
    ) {
        $this->entityManager = $entityManager;
        $this->security = $security;
        $this->doctorPatientRepository = $doctorPatientRepository;
    }

    public function add($form, User $patient): Appointment
    {
        $doctor = $this->security->getUser();
        $appointment = $form->getData();

        $receptionist = $this->doctorPatientRepository->findOneBy([
            'doctor' => $doctor,
            'patient' => $patient
        ]);

        $appointment->setReceptionist($receptionist);

        $this->entityManager->persist($appointment);
        $this->entityManager->flush();

        return $appointment;
    }
}
