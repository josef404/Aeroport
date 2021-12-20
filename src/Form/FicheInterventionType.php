<?php

namespace App\Form;

use App\Entity\Emplacement;
use App\Entity\FicheIntervention;
use App\Entity\Machine;
use App\Entity\PieceRechange;

use App\Entity\SousTraitant;
use App\Entity\Techniciens;
use App\Entity\User;
use App\Repository\TechniciensRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
class FicheInterventionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder

            ->add('date_debut', DateTimeType::class, [

                'label' => "Date debut intervention",
                'widget' => 'single_text',
                'required' => false,

                'attr' => [
                    'class' => 'datetimepicker '
                ]
            ])
            ->add('date_fin', DateTimeType::class, [
                'label' => "Date fin intervention",
                'widget' => 'single_text',
                'required' => false,

                'attr' => [
                    'class' => 'datetimepicker form-control'
                ]
            ])
            ->add('description')
            ->add('reference')

            #->add('activation')
            ->add('machine', EntityType::class, ['placeholder'=>'Select','class'=>Machine::class, 'choice_label'=>'libelle'])

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => FicheIntervention::class,
            "csrf_protection" => "false",
            "allow_extra_fields" => true
        ]);
    }
}
