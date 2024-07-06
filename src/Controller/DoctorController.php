<?php

namespace App\Controller;

use App\Business\User\UserUpdater;
use App\Form\UserForm;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/doctor', name: 'doctor_')]
class DoctorController extends AbstractController
{
    #[Route('/patients', name: 'patients_list')]
    public function patientsList()
    {
        return $this->render('doctor/index.html.twig');
    }

    #[Route('/profile', name: 'profile')]
    public function profile(
        UserUpdater $updater,
        Request $request
    ): Response {
        $user = $this->getUser();
        $form = $this->createForm(UserForm::class, $user, ['uploadAvatar' => true]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $updater->save($form);

            $this->addFlash('success', 'You successfully updated company.');
            return $this->redirectToRoute('patient_profile');
        }

        return $this->render('doctor/profile.html.twig', [
            'form' => $form->createView(),
            'title' => 'Редагування профілю',
        ]);
    }

    #[Route('/add-patient', name: 'add_patient')]
    public function addPatient()
    {
        return $this->render('doctor/index.html.twig');
    }
}
