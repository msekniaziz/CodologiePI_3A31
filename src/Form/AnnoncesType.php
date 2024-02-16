<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\File\File;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use App\Entity\Annonces;

class AnnoncesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Title',TextType::class)
            ->add('Description', TextareaType::class, [
                'attr' => ['rows' => 7],
            ])
            ->add('Negociable', CheckboxType::class, [
                'label'    => 'Show this entry publicly?',
                'required' => false,
                'data'     => true,  // Setting default value to true if the checkbox is not checked
            ])
            ->add('image', FileType::class, [
                'label' => 'Images (JPEG or PNG files)',
                'required' => false,
                'multiple' => true,
            ])



            ->add('Category',ChoiceType::class, [
                'choices' => [
                    'House' => 'House',
                    'Garden' => 'Garden',
                ],
            ])
            ->add('Prix')


            ->add('Add', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-primary font-weight-bold mt-3',
                ],
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Annonces::class, // Spécifiez la classe d'entité pour le mapping des données
        ]);
    }
}
