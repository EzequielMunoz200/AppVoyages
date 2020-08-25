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
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;
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
                'label' => 'Un pays en particulier?',
                'mapped' => false,
                'required' => false,
                'choices' => [
                    'Recherche par pays' => $this->em->getRepository(City::class)->findAllCountryName()
                ],
                'choice_label' => 'country',
                'choice_value' => 'id',
                'help' => 'Sans sélection, la recherche prendra en compte tous les pays.',
            ])

            ->add('tags', ChoiceType::class, [
                'label' => 'Choisissez une ou plusieurs étiquettes ci-dessous',
                'mapped' => false,
                'choices' => $this->em->getRepository(Tag::class)->findAll(),
                'choice_label' => 'name',
                'choice_value' => 'id',
                'multiple' => true,
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Vous devez ajouter des critères',
                    ]),
                ],
            ])
            ->add('erase', ResetType::class, [
                'attr' => [ 
                    'onclick' => 'myReset()',
                    'class' => 'btn-link float-sm-right'
                ],
                'label' => 'Reset',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => AdvancedSearchData::class,
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
