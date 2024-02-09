<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType; // Import DateType from Symfony form component
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class UserAddType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class)
            ->add('prenom', TextType::class)
            ->add('mail', TextType::class, [
                'constraints' => [new Assert\Email()],
            ])
            ->add('date_birthday', DateType::class) // Use DateType from Symfony form component
            ->add('tel', TextType::class, [
                'constraints' => [
                    new Assert\Length([
                        'min' => 8,
                        'max' => 8,
                        'exactMessage' => 'The telephone number must consist of exactly 8 characters.',
                    ]),
                    new Assert\Regex([
                        'pattern' => '/^\d{8}$/',
                        'message' => 'The telephone number must consist of 8 numeric characters.',
                    ]),
                ],
            ])


            ->add('gender', ChoiceType::class, [
                'choices' => [
                    'Man' => 'Man',
                    'Women' => 'Women',
                ],
            ])
            ->add('password', PasswordType::class)
            ->add('Confirmpassword', PasswordType::class)
            ->add('age', TextType::class)
            ->add('Register', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-primary font-weight-bold mt-3',
                ],
            ]); //class lkol homa nafshom eli mwjoudin fi template bootstrap
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
