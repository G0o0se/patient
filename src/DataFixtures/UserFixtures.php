<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Utils\CodeGenerator;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{

    private UserPasswordHasherInterface $hasher;

    private CodeGenerator $codeGenerator;

    public function __construct(
        UserPasswordHasherInterface $hasher,
        CodeGenerator $codeGenerator
    ) {
        $this->hasher = $hasher;
        $this->codeGenerator = $codeGenerator;
    }
    public function load(ObjectManager $manager): void
    {
        $admin = new User();
        $admin->setEmail('admin@mail.com');
        $admin->setRoles([User::ROLE_ADMIN]);
        $password = $this->hasher->hashPassword($admin, 'admin');
        $admin->setPassword($password);
        $admin->setFirstName('Admin');
        $admin->setLastName('Admin');
        $admin->setCode($this->codeGenerator->generateCode());
        $manager->persist($admin);

        $patient = new User();
        $patient->setEmail('patient@mail.com');
        $patient->setRoles([User::ROLE_PATIENT]);
        $password = $this->hasher->hashPassword($patient, 'patient');
        $patient->setPassword($password);
        $patient->setFirstName('Patient');
        $patient->setLastName('Patient');
        $patient->setCode($this->codeGenerator->generateCode());
        $manager->persist($patient);

        $doctor = new User();
        $doctor->setEmail('doctor@mail.com');
        $doctor->setRoles([User::ROLE_DOCTOR]);
        $password = $this->hasher->hashPassword($doctor, 'doctor');
        $doctor->setPassword($password);
        $doctor->setFirstName('Doctor');
        $doctor->setLastName('Doctor');
        $doctor->setCode($this->codeGenerator->generateCode());
        $manager->persist($doctor);

        foreach (range(1, 10) as $i) {
            $patient = new User();
            $patient->setEmail('patient'.$i.'@mail.com');
            $patient->setRoles([User::ROLE_PATIENT]);
            $password = $this->hasher->hashPassword($patient, 'patient');
            $patient->setPassword($password);
            $patient->setFirstName('Patient');
            $patient->setLastName('Patient');
            $patient->setCode($this->codeGenerator->generateCode());
            $manager->persist($patient);
        }

        $this->setReference('admin', $admin);
        $this->setReference('doctor', $doctor);
        $this->setReference('patient', $patient);

        $manager->flush();
    }
}
