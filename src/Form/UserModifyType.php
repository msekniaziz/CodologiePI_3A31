<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserModifyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', null, [
                'label' => 'Nom ',
                'empty_data' => ''
            ])
            // Add other fields as necessary
            ->add('prenom',TextType::class, [
                'label' => 'prenom ',
                'empty_data' => ''
            ])
            ->add('mail',TextType::class, [
                'label' => 'mail ',
                'empty_data' => ''
            ])
            ->add('tel',TextType::class, [
                'label' => 'tel ',
                'empty_data' => ''
            ])
            ->add('Edit', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-main-sm',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
