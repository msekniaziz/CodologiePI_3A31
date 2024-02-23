<?php

namespace App\Form;

use App\Entity\ReponseBlog;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\TextType;


class ReponseBlogType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('contenu', TextType::class,[
                'required' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'La description ne peut pas être vide',
                    ]),
                ],
            ])
            ->add('date')
            ->add('nb_likes')
            ->add('blog')
            ->add('id_user_reponse')
            ->add('save',SubmitType::class,[
                'label'=>'Save',
                'attr'=> ['class'=> 'btn btn-primary mt-3']
              ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ReponseBlog::class,
        ]);
    }
}
