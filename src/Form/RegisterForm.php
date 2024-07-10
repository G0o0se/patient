<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\IsTrue;

class RegisterForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
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
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'required' => false,
                'label' => 'Я погоджуюсь з умовами',
                'constraints' => [
                    new IsTrue([
                        'message' => 'Ви повинні погодитися з нашими умовами.',
                    ]),
                ],
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'required' => false,
                'first_options' => [
                    'label' => 'Password',
                    'constraints' => [
                        new Assert\NotNull([
                            'message' => 'Будь ласка, заповніть це поле',
                        ]),
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
                    'label' => 'Repeat.password',
                    'constraints' => [
                        new Assert\NotNull([
                            'message' => 'Будь ласка, заповніть це поле',
                        ]),
                    ]
                ],
                'invalid_message' => 'Значення не співпадають',
                'mapped' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}