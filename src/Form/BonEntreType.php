<?php

namespace App\Form;

use App\Entity\BonEntre;
use App\Entity\Demontage;
use App\Entity\FicheIntervention;
use App\Entity\Fournisseur;
use App\Entity\Machine;
use App\Entity\PieceRechange;
use App\Repository\MachineRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BonEntreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder



            ->add('id', HiddenType::class)
            ->add('fournisseur', EntityType::class, ['placeholder'=>'Select','class'=>Fournisseur::class, 'choice_label'=>'nom', 'required'=>false])

            ->add('demontage', EntityType::class, ['placeholder'=>'Select','class'=>Machine::class,
                'query_builder' => function (MachineRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->from(Demontage::class, 'm')
                        ->select('u')

                        ->where("u.id=m.machine");

                },
                'choice_label'=>'libelle',
                'required' => false])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => BonEntre::class,
            "csrf_protection" => "false",
            "allow_extra_fields" => true
        ]);
    }
}
