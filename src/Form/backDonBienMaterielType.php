<?php

namespace App\Form;

use App\Entity\DonBienMateriel;
use DateTime;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType; // Import DateType from Symfony form component
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
class backDonBienMaterielType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
      
        ->add('statutDon')
            ->add('dateDonBienMateriel', DateType::class, [
                'disabled' => true,
            ])
            ->add('id_association', TextType::class, [
                'disabled' => true,
            ])
            ->add('user_id', TextType::class, [
                'disabled' => true,
            ])
            
            ->add('Register', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-primary font-weight-bold mt-3',
                ],
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => DonBienMateriel::class,
        ]);
    }
}
