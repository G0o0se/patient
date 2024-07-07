<?php
namespace App\Business\User;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserUpdater
{

    private EntityManagerInterface $entityManager;

    private UserPasswordHasherInterface $userPasswordHasher;

    public function __construct(
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $userPasswordHasher
    ) {
        $this->entityManager = $entityManager;
        $this->userPasswordHasher = $userPasswordHasher;
    }

    public function save($form): User
    {
        /** @var User $user */
        $user = $form->getData();

        if(isset($form['avatar'])) {
            $avatar = $form['avatar']->getData();

            if ($avatar) {
                $newFilename = uniqid() . '.' . $avatar->guessExtension();

                $directoryPath = $_SERVER['DOCUMENT_ROOT'] . User::IMAGE_FOLDER;
                if (!file_exists($directoryPath)) {
                    mkdir($directoryPath, 0775, true);
                }

                $avatar->move(
                    $directoryPath,
                    $newFilename
                );
                $user->setAvatarPath($newFilename);
            }
        }

        if(isset($form['password'])) {
            $password = $form['password']->getData();

            if ($password) {
                $password = $this->userPasswordHasher->hashPassword($user, $password);
                $user->setPassword($password);
            }
        }

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }
}
