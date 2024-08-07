<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Utils\CodeGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\KeyValueStore;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Validator\Constraints as Assert;

class UserCrudController extends AbstractCrudController
{

    public function __construct(
        public UserPasswordHasherInterface $userPasswordHasher,
        public CodeGenerator $codeGenerator
    ) {}

    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureFields(string $pageName): iterable
    {
        $fields = [
            IdField::new('id')->hideOnForm(),
            TextField::new('firstName')
                ->setLabel('firstName'),
            TextField::new('lastName')
                ->setLabel('lastName'),
            EmailField::new('email')
                ->setLabel('Email'),
            ChoiceField::new('roles')
                ->setLabel('Roles')
                ->setChoices([
                    'Admin' => User::ROLE_ADMIN,
                    'Patient' => User::ROLE_PATIENT,
                    'Doctor' => User::ROLE_DOCTOR,
                ])
                ->allowMultipleChoices(),
            TextField::new('code')
                ->setLabel('Code')->setFormTypeOption('disabled', true)
        ];

        $avatarPath = ImageField::new('avatarPath')
            ->setBasePath(User::IMAGE_FOLDER)
            ->setUploadDir('public'.User::IMAGE_FOLDER)
            ->setUploadedFileNamePattern('[randomhash].[extension]')
            ->setRequired(false)
            ->onlyOnForms();

        $fields[] = $avatarPath;

        $password = TextField::new('password')
            ->setFormType(RepeatedType::class)
            ->setFormTypeOptions([
                'type' => PasswordType::class,
                'first_options' => ['label' => 'Password',
                    'constraints' => [
                        new Assert\Length([
                            'min' => 8,
                            'minMessage' => 'Ваш пароль повинен містити щонайменше {{ limit }} символів',
                        ]),
                        new Assert\Regex([
                            'pattern' => '/[!@#$%^&*()\-_=+{};:,<.>]/',
                            'match' => true,
                            'message' => 'Ваш пароль повинен містити принаймні один спеціальний символ',
                        ]),
                    ],],
                'second_options' => ['label' => 'Repeat.password'],
                'invalid_message' => 'Значення не співпадають',
                'mapped' => false,
            ])
            ->setRequired($pageName === Crud::PAGE_NEW)
            ->onlyOnForms();

        $fields[] = $password;

        return $fields;
    }

    public function createEntity(string $entityFqcn)
    {
        $user = new User();
        $user->setCode($this->codeGenerator->generateCode());
        return $user;
    }

    public function createNewFormBuilder(EntityDto $entityDto, KeyValueStore $formOptions, AdminContext $context): FormBuilderInterface
    {
        $formBuilder = parent::createNewFormBuilder($entityDto, $formOptions, $context);
        return $this->addPasswordEventListener($formBuilder);
    }

    public function createEditFormBuilder(EntityDto $entityDto, KeyValueStore $formOptions, AdminContext $context): FormBuilderInterface
    {
        $formBuilder = parent::createEditFormBuilder($entityDto, $formOptions, $context);
        return $this->addPasswordEventListener($formBuilder);
    }

    private function addPasswordEventListener(FormBuilderInterface $formBuilder): FormBuilderInterface
    {
        return $formBuilder->addEventListener(FormEvents::POST_SUBMIT, $this->hashPassword());
    }

    private function hashPassword() {
        return function($event) {
            $form = $event->getForm();
            if (!$form->isValid()) {
                return;
            }
            $password = $form->get('password')->getData();
            if ($password === null) {
                return;
            }

            $hash = $this->userPasswordHasher->hashPassword($this->getUser(), $password);
            $form->getData()->setPassword($hash);
        };
    }
}
