<?php

namespace App\Controller;

use App\Business\Doctor\PatientAddition;
use App\Business\User\UserUpdater;
use App\Entity\Patient;
use App\Form\AddPatientForm;
use App\Form\UserForm;
use App\Repository\PatientRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/doctor', name: 'doctor_')]
class DoctorController extends AbstractController
{
    #[Route('/patients', name: 'patients_list')]
    public function patientsList(
        PatientRepository $repository,
        PaginatorInterface $paginator,
        Request $request
    ) : Response {
        $doctor = $this->getUser();
        $query = $repository->findAllPatientsByDoctor($doctor);

        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            16
        );

        return $this->render('doctor/patient_list.html.twig', [
            'pagination' => $pagination,
            'title' => 'Список пацієнтів',
        ]);
    }

    #[Route('/patient/{id}/info', name: 'patient_info')]
    public function patientInfo(
        Patient $patient,
    ) : Response {
        return $this->render('doctor/patient_info.html.twig', [
            'patient' => $patient,
        ]);
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

            $this->addFlash('success', 'Ви успішно оновили профіль.');
            return $this->redirectToRoute('doctor_profile');
        }

        return $this->render('doctor/profile.html.twig', [
            'form' => $form->createView(),
            'title' => 'Редагування профілю',
        ]);
    }

    #[Route('/patient/add', name: 'add_patient')]
    public function addPatient(
        PatientAddition $patientAddition,
        Request $request
    ): Response {
        $form = $this->createForm(AddPatientForm::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $patient = $patientAddition->add($form);

            if($patient instanceof Response) {
                $this->addFlash('danger', $patient->getContent());
                return $this->redirectToRoute('doctor_add_patient');
            }

            $this->addFlash('success', 'Ви успішно додали пацієнта.');
            return $this->redirectToRoute('doctor_patients_list');
        }

        return $this->render('doctor/patient_add.html.twig', [
            'form' => $form->createView(),
            'title' => 'Додавання пацієнта',
        ]);
    }
}
