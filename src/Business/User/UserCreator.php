<?php
namespace App\Business\User;

use App\Entity\User;
use App\Utils\CodeGenerator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserCreator
{

    private EntityManagerInterface $entityManager;

    private UserPasswordHasherInterface $userPasswordHasher;

    private CodeGenerator $codeGenerator;

    public function __construct(
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $userPasswordHasher,
        CodeGenerator $codeGenerator
    ) {
        $this->entityManager = $entityManager;
        $this->userPasswordHasher = $userPasswordHasher;
        $this->codeGenerator = $codeGenerator;
    }

    public function save($form): User
    {
        /** @var User $user */
        $user = $form->getData();

        $password = $this->userPasswordHasher->hashPassword($user, $form->get('password')->getData());
        $user->setPassword($password);
        $user->setRoles([User::ROLE_PATIENT]);
        $user->setCode($this->codeGenerator->generateCode());

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }
}
