<?php
namespace App\Business\User;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class UserUpdater
{

    private EntityManagerInterface $entityManager;

    public function __construct(
        EntityManagerInterface $entityManager
    ) {
        $this->entityManager = $entityManager;
    }

    public function save($form): User
    {
        /** @var User $user */
        $user = $form->getData();

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }
}
