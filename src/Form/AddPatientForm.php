<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class AddPatientForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('code', null, [
                'label' => 'Code',
                'required' => false,
                'constraints' => [
                    new Assert\NotNull([
                        'message' => 'Будь ласка, заповніть це поле',
                    ]),
                    new Assert\Regex([
                        'pattern' => '/^[a-zA-Z0-9]+$/',
                        'match' => true,
                        'message' => 'Код повинен містити лише латинські літери на цифри',
                    ]),
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => null,
        ]);
    }
}