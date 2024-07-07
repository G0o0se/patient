<?php

namespace App\Controller;

use App\Business\User\UserUpdater;
use App\Form\UserForm;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/patient', name: 'patient_')]
class PatientController extends AbstractController
{
    #[Route('/information', name: 'information')]
    public function information()
    {
        return $this->render('patient/index.html.twig');
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
