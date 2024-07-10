<?php

namespace App\DataFixtures;

use App\Entity\DoctorPatient;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class PatientFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $patient = new DoctorPatient();
        $patient->setPatient($this->getReference('patient'));
        $patient->setDoctor($this->getReference('doctor'));

        $manager->persist($patient);

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
        ];
    }
}
