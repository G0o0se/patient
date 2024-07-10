<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\Image;

class UserForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $uploadAvatar = $options['uploadAvatar'];

        $builder
            ->add('firstName', null, [
                'label' => 'firstName',
                'required' => false,
            ])
            ->add('lastName', null, [
                'label' => 'lastName',
                'required' => false,
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'required' => false,
            ]);

            if($uploadAvatar) {
                $builder
                    ->add('avatar', FileType::class, [
                        'label' => 'Avatar',
                        'mapped' => false,
                        'required' => false,
                        'constraints' => [
                            new Image([
                                'maxSize' => '1024k',
                                'mimeTypes' => [
                                    'image/jpeg',
                                    'image/png',
                                ],
                                'mimeTypesMessage' => 'Будь ласка, завантажте дійсне зображення у форматі PNG або JPEG',
                            ])
                        ],
                    ]);
            }

        $builder
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'required' => false,
                'first_options' => [
                    'label' => 'New password',
                    'required' => false,
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
                    ],
                ],
                'second_options' => [
                    'label' => 'Repeat.new.password',
                    'required' => false,
                ],
                'invalid_message' => 'Значення не співпадають',
                'mapped' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'uploadAvatar' => false
        ]);
    }
}