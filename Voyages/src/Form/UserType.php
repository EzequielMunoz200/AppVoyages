<?php

namespace App\Form;

use App\Entity\Language;
use App\Entity\User;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\Length;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstname', TextType::class, [
                'label' => 'Prénom',
                'attr' => [ 'class' => 'form-group' ],
            ])
            ->add('name', TextType::class, [
                'label' => 'Nom',
            ])
            ->add('username', TextType::class, [
                'label' => 'Pseudo',
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'disabled' => true,
            ])
            ->add('avatar', FileType::class, [
                'label' => 'Avatar',
                'mapped' => false,
                'constraints' => [
                    new Image([
                        'maxSize' => '1024k',
                        //'maxSizeMessage' => 'Fichier trop grand',
                    ])
                ]
            ])
            ->add('description', CKEditorType::class, [
                'label' => 'Présentation',
                'constraints' =>  [
                    new Length([
                        'min' => 25,
                        'max' => 2000,
                        'minMessage' => "{{ limit }} caractères minimum",
                        'maxMessage' => "{{ limit }} caractères maximum",
                        'allowEmptyString' => false,
                    ]),
                ]
            ])
            ->add('languages', EntityType::class, [
                'label' => 'Langues Parlées',
                'class' => Language::class,
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' => true,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'attr' => [
                'novalidate' => 'novalidate', 
                'class' => 'd-flex flex-column justify-content-xl-center  justify-content-lg-center col-12 col-lg-12 col-xl-12',
            ],
        ]);
    }
}
