<?php

namespace App\Form;
use App\Entity\Association;
use App\Entity\DonArgent;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class DonArgentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('montant', null, [
            'required' => false,
            'label' => '<strong>Amount</strong>',
            // 'help' => 'you should put the description',
            'label_html' => true, // Permet d'interpréter le label comme du HTML
        ])
            ->add('dateDonArgent', null, [
                'label' => '<strong>Date of Donation</strong>', // Add your desired label here
                'label_html' => true,
            ])
            // ->add('user_id')
            ->add('id_association', EntityType::class, [
                'class' => Association::class,
                'label' => '<strong>Organization name</strong>',
                // 'help' => 'you should put the description',
                'label_html' => true, // Permet d'interpréter le label comme du HTML
                
                // 'choice' => [$options['association']], // Utilisez l'option association ici
                // Autres options pour le champ d'entité
            ])

            ->add('Checkout', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-primary font-weight-bold mt-3',
                    // 'formaction' => 'checkout', // Redirige vers la route de paiement
                ],
            ])
            ;
        } 
            //class lkol homa nafshom eli mwjoudin fi template bootstrap
          
        //     ->add('save', SubmitType::class, [
        //         'label' => $options['edit_mode'] ? 'Edit' : 'Add',
        //         'attr' => [
        //             'class' => 'btn btn-primary font-weight-bold mt-3',
                   
        //         ],
        //     ])
        // ;
    

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => DonArgent::class,
            // 'edit_mode' => false, // Par défaut, le mode édition est désactivé
        ]);
    }
}
