<?php
namespace App\Business\Doctor;

use App\Entity\Patient;
use App\Repository\PatientRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;

class PatientAddition
{

    private EntityManagerInterface $entityManager;

    private UserRepository $userRepository;

    private Security $security;

    private PatientRepository $patientRepository;

    public function __construct(
        EntityManagerInterface $entityManager,
        UserRepository $userRepository,
        Security $security,
        PatientRepository $patientRepository
    ) {
        $this->entityManager = $entityManager;
        $this->userRepository = $userRepository;
        $this->security = $security;
        $this->patientRepository = $patientRepository;
    }

    public function add($form): Patient|Response
    {
        $data = $form->getData();

        $code = $data['code'];
        $user = $this->userRepository->getPatient($code);
        $doctor = $this->security->getUser();

        $patientIsAddedToDoctor = $this->patientRepository->getPatientByDoctor($doctor, $user);

        if(!is_null($patientIsAddedToDoctor)) {
            return new Response('Пацієнт вже доданий до Вас', 400);
        }

        if(!is_null($user)) {
            $patient = new Patient();
            $patient->setPatient($user);
            $patient->setDoctor($doctor);

            $this->entityManager->persist($patient);
            $this->entityManager->flush();

            return $patient;
        } else {
            return new Response('Пацієнт не знайдений', 400);
        }
    }
}
