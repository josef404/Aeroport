<?php

namespace App\Form;

use App\Entity\BonSortie;
use App\Entity\Emplacement;
use App\Entity\FicheIntervention;
use App\Entity\Machine;
use App\Entity\PieceRechange;
use App\Repository\FicheInterventionRepository;
use App\Repository\MachineRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BonSortieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('id', HiddenType::class)
            ->add('intervention', EntityType::class, ['placeholder'=>'Select','class'=>FicheIntervention::class,
                'query_builder' => function (FicheInterventionRepository $er) {
                    return $er->createQueryBuilder('u')

                        ->select('u')

                        ->where("u.activation=true");

                },
                'choice_label'=>'reference'])
            #affichage des machines qui on besoin d'entretien seulement dans la liste


        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => BonSortie::class,
            "csrf_protection" => "false",
            "allow_extra_fields" => true
        ]);
    }
}
