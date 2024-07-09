<?php

namespace App\Form;

use App\Entity\Appointment;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class AddAppointmentForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('action', null, [
                'label' => 'Actions',
                'required' => false,
                'constraints' => [
                    new Assert\Count([
                        'min' => 1,
                        'minMessage' => 'Будь ласка, виберіть принаймні один варіант',
                    ]),
                    new Assert\NotNull([
                        'message' => 'Будь ласка, заповніть це поле',
                    ]),
                ]

            ])
            ->add('conclusion', null, [
                'label' => 'Conclusion',
                'required' => false,
                'constraints' => [
                    new Assert\NotNull([
                        'message' => 'Будь ласка, заповніть це поле',
                    ]),
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Appointment::class,
        ]);
    }
}
