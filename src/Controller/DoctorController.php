<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/doctor', name: 'doctor_')]
class DoctorController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index()
    {
        echo 'Doctor index';
    }
}
