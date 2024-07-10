<?php
namespace App\Business\Doctor;

use App\Entity\DoctorPatient;
use App\Repository\DoctorPatientRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\Translation\TranslatorInterface as Translator;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;

class PatientAddition
{

    private EntityManagerInterface $entityManager;

    private UserRepository $userRepository;

    private Security $security;

    private DoctorPatientRepository $patientRepository;

    private Translator $translator;

    public function __construct(
        EntityManagerInterface  $entityManager,
        UserRepository          $userRepository,
        Security                $security,
        DoctorPatientRepository $patientRepository,
        Translator              $translator
    ) {
        $this->entityManager = $entityManager;
        $this->userRepository = $userRepository;
        $this->security = $security;
        $this->patientRepository = $patientRepository;
        $this->translator = $translator;
    }

    public function add($form): DoctorPatient|Response
    {
        $data = $form->getData();

        $code = $data['code'];
        $user = $this->userRepository->getPatient($code);
        $doctor = $this->security->getUser();

        $patientIsAddedToDoctor = $this->patientRepository->getPatientByDoctor($doctor, $user);

        if(!is_null($patientIsAddedToDoctor)) {
            $message = $this->translator->trans('error.patient.alreadyAdded');
            return new Response($message, 400);
        }

        if(!is_null($user)) {
            $patient = new DoctorPatient();
            $patient->setPatient($user);
            $patient->setDoctor($doctor);

            $this->entityManager->persist($patient);
            $this->entityManager->flush();

            return $patient;
        } else {
            $message = $this->translator->trans('error.patient.notFound');
            return new Response($message, 400);
        }
    }
}
