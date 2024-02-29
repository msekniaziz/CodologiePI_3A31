<?php

namespace App\Form;
use App\Entity\Association;
use App\Entity\DonBienMateriel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType; // Import DateType from Symfony form component
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
class DonBienMaterielType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('photoDon', FileType::class, [
    'label' => '<strong>Picture of donation</strong>',
    'required' => false, // Le champ n'est pas requis, car il peut être vide
    'mapped'=>false,
    'label_html' => true,
])

        

->add('descriptionDon', null, [
    'required' => false,
    'label' => '<strong>Description</strong>',
    // 'help' => 'you should put the description',
    'label_html' => true, // Permet d'interpréter le label comme du HTML
])
        // ->add('statutDon', null, [
        //     'required' => false,
        //     'attr' => [
        //         'style' => 'display: none;'
        //     ]
        // ])
        ->add('dateDonBienMateriel', null, [
            'label' => '<strong>Date of Donation</strong>', // Add your desired label here
            'label_html' => true,
        ])
        
            
            ->add('id_association', EntityType::class, [
                 'class' => Association::class,
                 'label' => '<strong>Organization name</strong>',
                 // 'help' => 'you should put the description',
                 'label_html' => true, // Permet d'interpréter le label comme du HTML
                 
                 // 'choice' => [$options['association']], // Utilisez l'option association ici
                 // Autres options pour le champ d'entité
             ])
           
           
            
            // ->add('user_id')
            ->add('save', SubmitType::class, [
                'label' => $options['edit_mode'] ? 'Edit' : 'Add',
                'attr' => [
                    'class' => 'btn btn-primary font-weight-bold mt-3',
                ],
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => DonBienMateriel::class,
            'edit_mode' => false, // Par défaut, le mode édition est désactivé
        ]);
    }
}
