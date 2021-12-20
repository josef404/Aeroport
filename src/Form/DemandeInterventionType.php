<?php

namespace App\Form;

use App\Entity\DemandeIntervention;
use App\Entity\Demontage;
use App\Entity\Machine;
use App\Entity\PieceRechange;
use App\Entity\SousTraitant;
use App\Entity\Techniciens;
use App\Entity\User;
use App\Repository\TechniciensRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DemandeInterventionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder

            ->add('description')
            ->add('machine', EntityType::class, ['placeholder'=>'Select','class'=>Machine::class, 'choice_label'=>'libelle','multiple' => false])
            ->add('technicien', EntityType::class, ['class'=>Techniciens::class, 'choice_label'=>'prenom'.'nom',
                'required' => true,
                'mapped'=>false,
                'attr' => [
                    'class' => 'select2 form-control select2-multiple',
                    'multiple' => true
                ],])
            ->add('sous_traitant', EntityType::class, ['placeholder'=>'Select','class'=>SousTraitant::class, 'choice_label'=>'nom','multiple' => false
                ,'required' => false])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => DemandeIntervention::class,
        ]);
    }
}
