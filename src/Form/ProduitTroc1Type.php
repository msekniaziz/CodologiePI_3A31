<?php

namespace App\Form;

use App\Entity\ProduitTroc;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\HttpFoundation\File\File;

use PHPUnit\TextUI\Help;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\FileType;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Filesystem\Exception\FileException;


class ProduitTroc1Type extends AbstractType
{
    
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
       
        $builder
        ->add('nom', null, [
            'attr' => ['class' => 'form-control'],
            'required' => false,
          
        ])
        
            
        ->add('category', ChoiceType::class, [
            'choices' => [
                'House' => 'house',
                'Garden' => 'Garden',
                // Add more options as needed
            ],
            'placeholder' => 'Choose a category', // Optional placeholder
            'required' => false, // Mark as required

            ])
        ->add('description', null, [
            'label' => 'Description',
            'attr' => ['class' => 'form-control'],
            'required' => false, // Mark as required
        ])
        ->add('statut', null, [
            'required' => false,
            'attr' => [
                'style' => 'display: none;',
            ]
    ])
       // ->add('image',filetype::class,[
         //   'required' => false,
       //])
        
   ->add('image', FileType::class, [
      'label' => 'Image ',
      'mapped' => false, // This field is not mapped to any entity property

    'required' => false, // Allow the field to be optional
   ])
        ->add('nom_produit_recherche',null,[
            'label' => 'Product you are searching for :',

            'required' => false,
        ])
        // ->add('id_user')
      ->add('save',SubmitType::class,[
        'label'=>'Save',
        'attr'=> ['class'=> 'btn btn-primary mt-3']
      ])

    ;
    $builder->get('image')->addModelTransformer(new FileToViewTransformer());     

    }


    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Adjust the entity class if needed
            'data_class' => ProduitTroc::class,
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
