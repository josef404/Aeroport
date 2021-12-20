<?php

namespace App\Form;

use App\Entity\Demontage;
use App\Entity\FicheIntervention;
use App\Entity\Machine;
use App\Repository\MachineRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DemontageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('piece_rechange')
            ->add('machine', EntityType::class, ['placeholder'=>'Select','class'=>Machine::class,
                'query_builder' => function (MachineRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->from(FicheIntervention::class, 'm')
                        ->select('u')

                        ->where("u.id=m.machine");

                },
                'choice_label'=>'libelle'])

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Demontage::class,
        ]);
    }
}
