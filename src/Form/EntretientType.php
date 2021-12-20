<?php

namespace App\Form;

use App\Entity\Entretient;
use App\Entity\Machine;
use App\Repository\MachineRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EntretientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            ->add('date_prochain_entretient', DateType::class, [

                'label' => "Date prochain entretient",
                'widget' => 'single_text',
                'required' => false,

                'attr' => [
                    'class' => 'datepicker '
                ]
            ])


        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Entretient::class,
        ]);
    }
}
