<?php

namespace App\Form;

use App\Entity\Famille;
use App\Entity\SousFamille;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SousFamilleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('libelle')
            ->add('marque')
            ->add('fam_id', EntityType::class, ['placeholder'=>'Select','class'=>Famille::class, 'choice_label'=>'libelle'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SousFamille::class,
        ]);
    }
}
