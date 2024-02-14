<?php

namespace App\Form;

use App\Entity\PtCollect;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PtCollectType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Nom_pc')
            ->add('adresse_pc')
            ->add('latitude_pc')
            ->add('longitude_pc')
            ->add('type')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => PtCollect::class,
        ]);
    }
}
