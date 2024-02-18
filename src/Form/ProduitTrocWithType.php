<?php

namespace App\Form;

use App\Entity\ProduitTrocWith;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProduitTrocWithType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('category')
            ->add('description')
            ->add('image')
            ->add('prod_idTroc')
            ->add('user_id_Tchange')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ProduitTrocWith::class,
        ]);
    }
}
