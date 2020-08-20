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
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class SearchType extends AbstractType
{

    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->setMethod('GET')
            ->add('cities', ChoiceType::class, [
                'label' => 'Cherchez par nom de ville',
                'mapped' => false,
                'required' => true,
                'choices' => [
                    'Recherche par nom de ville' => $this->em->getRepository(City::class)->findAll(),
                ],
                'choice_label' => 'name',
                'choice_value' => 'geonameId',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez selectionner une ville',
                    ]),
                ],
            ]);

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => City::class,
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
