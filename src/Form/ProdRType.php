<?php

namespace App\Form;

use App\Entity\ProdR;
// use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
// use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints as Assert;
// use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
// use Vich\UploaderBundle\Form\Type\VichFileType;
// use Vich\UploaderBundle\Form\Type\VichImageType;

class ProdRType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // ->add('justificatif', FileType::class, [
            //     'label' => 'Image (JPEG or PNG file)',
            //     'required' => false, // Le champ n'est pas requis, car il peut être vide
            //     'mapped' => false,
            // ])
            // ->add('statut', TextType::class, [
            //     'constraints' => [
            //         new NotBlank([
            //             'message' => 'Le statut ne peut pas être vide',
            //         ]),
            //     ],
            // ])
            ->add('description', TextType::class, [
                'required' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'La description ne peut pas être vide',
                    ]),
                ],
            ])
            ->add('nomP', TextType::class, [
                'required' => false,
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

            // ->add('imageFile', VichImageType::class, [
            // ->add('imageFile', FileType::class, [
            //     'label' => 'Image de la recette',
            //     'mapped' => false,
            //     'label_attr' => [
            //         'class' => 'form-label mt-4'
            //     ],
            //     'required' => false
            // ])

            ->add('save', SubmitType::class, [
                'label' => 'Save',
                'attr' => ['class' => 'btn  btn-primary mt-3']
            ]);
            // Ajoutez d'autres champs avec des contraintes de validation si nécessaire

        ;
        // $builder->get('image')->addModelTransformer(new FileToViewTransformer());
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ProdR::class,
        ]);
    }
}
