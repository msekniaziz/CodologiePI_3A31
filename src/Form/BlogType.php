<?php

namespace App\Form;

use App\Entity\Blog;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Filesystem\Exception\FileException;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\TextType;


class BlogType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('category', ChoiceType::class, [
                'choices' => [
                    'House' => 'house',
                    'Garden' => 'Garden',
                    // Add more options as needed
                ],
    
                ])
            ->add('titre', TextType::class,[
                'required' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'La description ne peut pas être vide',
                 ]),
                ],
            ])
            ->add('contenu_blog')
            ->add('status')
           
            ->add('rate')
            ->add('save',SubmitType::class,[
                'label'=>'Save',
                'attr'=> ['class'=> 'btn btn-primary mt-3']
              ])
          //  ->add('user')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Blog::class,
        ]);
    }
    
}
