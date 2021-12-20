<?php

namespace App\Form;

use App\Entity\Machine;
use App\Entity\PieceRechange;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PieceRechangeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('designation')
            ->add('machine', EntityType::class, [
                'class' => Machine::class,

                'choice_label' => 'libelle',
                'attr' => [
                    'class' => 'select2 form-control select2-multiple',
                    'multiple' => true
                ],
                'mapped'=>false

            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => PieceRechange::class,
        ]);
    }
}
