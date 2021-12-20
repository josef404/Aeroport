<?php

namespace App\Form;

use App\Entity\Batiment;
use App\Entity\Emplacement;
use App\Entity\Famille;
use App\Entity\Machine;
use App\Entity\SousFamille;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MachineType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('libelle')
            ->add('batiment',HiddenType::class)
            ->add('num_serie')
            ->add('nbHeureTravail')
            ->add('date_installation', DateType::class, [
                'label' => "Date installation",
                'widget' => 'single_text',

                'attr' => [
                    'class' => 'datepicker form-control'
                ]
            ])
            ->add('sous_famille', EntityType::class, ['placeholder'=>'Select','class'=>SousFamille::class, 'choice_label'=>'libelle'])
            ->add('emplacement', EntityType::class, ['placeholder'=>'Select','class'=>Emplacement::class, 'choice_label'=>'libelle', 'mapped'=>false])
            ->add('batiment', EntityType::class, ['placeholder'=>'Select','class'=>Batiment::class, 'choice_label'=>'libelle'])

        ;





    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Machine::class,
        ]);
    }
}
