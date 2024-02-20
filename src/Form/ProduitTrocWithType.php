<?php

namespace App\Form;

use App\Entity\ProduitTrocWith;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use App\Entity\ProduitTroc;

use PHPUnit\TextUI\Help;


use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Filesystem\Exception\FileException;
class ProduitTrocWithType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
     
            ->add('category', ChoiceType::class, [
                'choices' => [
                    'House' => 'house',
                    'Garden' => 'Garden',
                    // Add more options as needed
                ],
                'placeholder' => 'Choose a category', // Optional placeholder
                'required' => false, // Mark as required
    
                ])
            ->add('description')
            ->add('image', FileType::class, [
                'label' => 'Image',
                'mapped' => false, // This field is not mapped to any entity property
                'required' => false, // This field is not required
                'data_class' => null, // Set data_class to null to avoid binding to a specific class
            ])
    
            //  ->add('prod_idTroc')
          //  ->add('user_id_Tchange')
          ->add('save',SubmitType::class,[
            'label'=>'Save',
            'attr'=> ['class'=> 'btn btn-primary mt-3']
          ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ProduitTrocWith::class,
        ]);
    }
}
