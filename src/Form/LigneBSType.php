<?php

namespace App\Form;

use App\Entity\LigneBS;
use App\Entity\PieceRechange;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LigneBSType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('quantite')

            ->add('piece', EntityType::class, ['class'=>PieceRechange::class, 'choice_label'=>'designation','multiple' => false])

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => LigneBS::class,
        ]);
    }
}
