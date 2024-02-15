<?php

namespace App\Form;

use App\Entity\ProdR;
// use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
// use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints as Assert;


class backProdRType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('statut')
            ->add('description', null, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'La description ne peut pas être vide',
                    ]),
                ],
            ])
            ->add('nomP', null, [
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Le champ nomP ne doit pas être vide.']),
                    new Assert\Regex([
                        'pattern' => '/^\D+$/',
                        'message' => 'Le champ nomP ne doit pas contenir de chiffres.',
                    ]),
                ],
            ])
            // ->add('user_id')
            ->add('ptc_id')
            ->add('typeProd_id', null, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'La description ne peut pas être vide',
                    ]),
                ],
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Save',
                'attr' => ['class' => 'btn  btn-primary mt-3']
            ]);
            // Ajoutez d'autres champs avec des contraintes de validation si nécessaire
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ProdR::class,
        ]);
    }
}
