<?php

namespace App\Form;

use App\Entity\Techniciens;

use Cassandra\Date;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TechniciensType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom')
            ->add('prenom')
            ->add('adress')
            ->add('telephone')
            ->add('dateNaissance', DateType::class, [
                'label' => "Date de naissance",
                'widget' => 'single_text',

                'attr' => [
                    'class' => 'datepicker form-control'
                ]
            ])
            ->add('dateRecrutement', DateType::class, [
                'label' => "Date de recrutement",
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
            'data_class' => Techniciens::class,
        ]);
    }
}
