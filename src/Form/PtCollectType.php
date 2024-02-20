<?php

namespace App\Form;

use App\Entity\PtCollect;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\NotBlank;

class PtCollectType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Nom_pc', null, [
                'required' => false,
                'constraints' => [
                    // new NotBlank(),
                    new Regex([
                        'pattern' => '/^[^\d]+$/',
                        'message' => 'Le nom du point de collecte ne doit pas contenir de chiffres.',
                    ]),
                ],
            ])
            ->add('adresse_pc', null, ['required' => false])
            ->add('latitude_pc', null, [
                'required' => false,
                'constraints' => [
                    new Regex([
                        'pattern' => '/^\d+(\.\d+)?$/',
                        'message' => 'La latitude_pc ne doit contenir que des nombres et un point.'
                    ]),
                ],
            ])
            ->add('longitude_pc', null, [
                'required' => false,
                'constraints' => [
                    new Regex([
                        'pattern' => '/^\d+(\.\d+)?$/',
                        'message' => 'La longitude_pc ne doit contenir que des nombres et un point.'
                    ]),
                ],
            ])
            ->add('type', null, [
                'required' => false,
                'constraints' => [
                    // new NotBlank(['message' => 'Veuillez choisir un type.']),
                ],
                // 'required' => false,
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Save',
                'attr' => ['class' => 'btn  btn-primary mt-3']
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => PtCollect::class,
        ]);
    }
}
