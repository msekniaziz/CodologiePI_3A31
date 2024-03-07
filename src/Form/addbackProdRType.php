<?php

namespace App\Form;

use App\Entity\ProdR;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints as Assert;

class addbackProdRType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
           ->add('statut')
            ->add('ptc_id')
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
            ->add('typeProd_id', null, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Le type de produit ne peut pas être vide',
                    ]),
                ],
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Save',
                'attr' => ['class' => 'btn btn-primary mt-3']
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ProdR::class,
        ]);
    }
}
