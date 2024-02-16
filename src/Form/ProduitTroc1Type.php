<?php

namespace App\Form;

use App\Entity\ProduitTroc;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

class ProduitTroc1Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
       
        $builder
        ->add('nom', null, [
            'attr' => ['class' => 'form-control'],
            'required' => false,
            'help' => 'You should put the name of your product',
            'help_attr' => ['class' => 'red-help'],
        ])
        
            
        ->add('category', ChoiceType::class, [
            'choices' => [
                'House' => 'house',
                'Garden' => 'Garden',
                // Add more options as needed
            ],
            'placeholder' => 'Choose a category', // Optional placeholder
        ])
        ->add('description', null, [
            'label' => 'Description',
            'attr' => ['class' => 'form-control'],
            'required' => false, // Mark as required
            'help' => 'you should put the description', // Custom error message
        ])
        ->add('statut', null, [
            'required' => false,
            'attr' => [
                'style' => 'display: none;'
            ]
        ])
        ->add('image',null,[
            'required' => false,
            'help' => 'you should put the image of your product',
        ])
        
        ->add('nom_produit_recherche',null,[
            'required' => false,
            'help' => 'you should put name of the product youre searching for',
        ])
        // ->add('id_user')
      ->add('save',SubmitType::class,[
        'label'=>'Save',
        'attr'=> ['class'=> 'btn btn-primary mt-3']
      ])

    ;
    }



    

    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Adjust the entity class if needed
            'data_class' => ProduitTroc::class,
        ]);
    }
}
