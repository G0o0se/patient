<?php

namespace App\Controller;

use App\Business\User\UserUpdater;
use App\Entity\User;
use App\Form\UserForm;
use App\Repository\AppointmentRepository;
use App\Repository\DoctorPatientRepository;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/patient', name: 'patient_')]
class PatientController extends AbstractController
{
    #[Route('/information', name: 'information')]
    public function information(
        AppointmentRepository $appointmentRepository,
        DoctorPatientRepository $doctorPatientRepository,
        Security $security
    ) : Response {
        $receptionists = $doctorPatientRepository->findBy([
            'patient' => $security->getUser()
        ]);

        $appointments = [];

        foreach ($receptionists as $receptionist) {
            $appointment = $appointmentRepository->findBy(['receptionist' => $receptionist->getId()]);
            $appointments = array_merge($appointments, $appointment);
        }

        return $this->render('patient/information.html.twig', [
            'patient' => $security->getUser(),
            'appointments' => $appointments,
        ]);
    }

    #[Route('/profile', name: 'profile')]
    public function profile(
        UserUpdater $updater,
        Request $request
    ): Response {
        $user = $this->getUser();
        $form = $this->createForm(UserForm::class, $user, ['uploadAvatar' => false]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $updater->save($form);

            $this->addFlash('success', 'Ви успішно оновили профіль.');
            return $this->redirectToRoute('patient_profile');
        }

        return $this->render('patient/profile.html.twig', [
            'form' => $form->createView(),
            'title' => 'Редагування профілю',
        ]);
    }
}
