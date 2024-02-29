<?php

namespace App\Form;

use App\Entity\Association;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType; // Import DateType from Symfony form component
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\FileType;



class AssociationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nomAssociation', null, [
                'label' => 'Name ', // Add your desired label here
            ])
            // ->add('logoAssociation')
            // ->add('logoAssociation', FileType::class, [
            //     'label' => 'Logo',
            //     'mapped' => false, // Ne pas mapper ce champ à une propriété de l'entité
            //     'required' => false,])// Le champ n'est pas obligatoire
            
            ->add('adresseAssociation', null, [
                'label' => 'Address ', // Add your desired label here
            ])
            ->add('RIB', null, [
                'label' => 'RIB', // Add your desired label here
            ])
           
            ->add('descriptionAsso', null, [
                'label' => 'Description', // Add your desired label here
            ])
            ->add('save', SubmitType::class, [
                'label' => $options['edit_mode'] ? 'Edit' : 'Add',
                'attr' => [
                    'class' => 'btn btn-primary font-weight-bold mt-3',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Association::class,
            'edit_mode' => false, // Par défaut, le mode édition est désactivé
        ]);
    }
}
