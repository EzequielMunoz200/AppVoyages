<?php

namespace App\Form;

use App\Data\AdvancedSearchData;
use App\Entity\City;
use App\Entity\Tag;
use App\Repository\TagRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class AdvancedSearchType extends AbstractType
{

    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->setMethod('GET')
            ->add('countries', ChoiceType::class, [
                'label' => 'Rechercher dans tous les pays ou un en particulier',
                'mapped' => false,
                'choices' => [
                    'Recherche par pays' => $this->em->getRepository(City::class)->findAllCountryName()
                ],
                'choice_label' => 'country',
                'choice_value' => 'id',
            ])

            ->add('tags', ChoiceType::class, [
                'label' => 'Ajoutez a votre recherche les critéres que vous cherchez dans une ville',
                'mapped' => false,
                'choices' => $this->em->getRepository(Tag::class)->findAll(),
                'choice_label' => 'name',
                'choice_value' => 'id',
                'multiple' => true,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'method' => 'GET',
            'csrf_protection' => false,
            'attr' => ['novalidate' => 'novalidate'],
        ]);
    }


    public function getBlockPrefix()
    {
        return '';
    }
}
