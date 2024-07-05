<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/patient', name: 'patient_')]
class PatientController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index()
    {
        echo 'patient index';
    }
}
