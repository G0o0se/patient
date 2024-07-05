<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{

    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }
    public function load(ObjectManager $manager): void
    {
        $admin = new User();
        $admin->setEmail('admin@mail.com');
        $admin->setRoles(['ROLE_ADMIN']);
        $password = $this->hasher->hashPassword($admin, 'admin');
        $admin->setPassword($password);
        $manager->persist($admin);

        $patient = new User();
        $patient->setEmail('patient@mail.com');
        $patient->setRoles(['ROLE_PATIENT']);
        $password = $this->hasher->hashPassword($patient, 'patient');
        $patient->setPassword($password);
        $manager->persist($patient);

        $doctor = new User();
        $doctor->setEmail('doctor@mail.com');
        $doctor->setRoles(['ROLE_DOCTOR']);
        $password = $this->hasher->hashPassword($doctor, 'doctor');
        $doctor->setPassword($password);
        $manager->persist($doctor);

        $manager->flush();
    }
}
