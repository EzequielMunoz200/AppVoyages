<?php

namespace App\Form;

use App\Entity\CityList;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CityListType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => ' Ajouter cette liste à vos favoris :',
                'attr' =>['placeholder' => 'Saisir un nom']
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => CityList::class,
            'attr' => [ 'class' => 'form_save_list'],
            'attr' => ['novalidate' => 'novalidate'],
        ]);
    }
}
