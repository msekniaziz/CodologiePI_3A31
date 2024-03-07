<?php

namespace App\Form;

use App\Entity\Category;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;

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
                'label' => 'Image (JPEG or PNG file)',
                'required' => false, // Le champ n'est pas requis, car il peut être vide
                'mapped'=>false,
            ])


            ->add('idCat', EntityType::class, [ // Ajoutez le champ de sélection de la catégorie
                'class' => Category::class, // Spécifiez l'entité cible pour la relation
                'choice_label' => 'name', // Choisissez la propriété de l'entité à afficher dans la liste déroulante
                'placeholder' => 'Select a category', // Texte à afficher par défaut dans la liste déroulante
            ])
            ->add('Prix')


            ->add('Add', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-primary font-weight-bold mt-3',
                ],
            ]);
        $builder->get('image')->addModelTransformer(new FileToViewTransformer());
        ;
    }


    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configurez ici les options par défaut pour votre formulaire
        ]);
    }
}
class FileToViewTransformer implements DataTransformerInterface
{
    public function transform($value)
    {
        // Lors de l'affichage du formulaire, retournez null pour éviter les erreurs
        return null;
    }

    public function reverseTransform($value)
    {
        // Transformez le chemin de fichier en instance de Symfony\Component\HttpFoundation\File\File
        // Si $value est null, retournez null
        if (!$value) {
            return null;
        }

        return new File($value);
    }
}