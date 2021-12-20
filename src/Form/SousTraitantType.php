<?php

namespace App\Form;

use App\Entity\SousTraitant;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SousTraitantType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom')
            ->add('numTel')
            ->add('date_debut_contrat', DateType::class, [
                'label' => "Date debut contrat",
                'widget' => 'single_text',

                'attr' => [
                    'class' => 'datepicker form-control'
                ]
            ])
            ->add('date_fin_contrat', DateType::class, [
                'label' => "Date fin contrat",
                'widget' => 'single_text',

                'attr' => [
                    'class' => 'datepicker form-control'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SousTraitant::class,
        ]);
    }
}
