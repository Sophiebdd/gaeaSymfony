<?php

namespace App\Form;

use App\Entity\Possessions;
use App\Entity\Users;
use Doctrine\ORM\Mapping\Id;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PossessionsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('valeur')
            ->add('type')
            ->add('users', EntityType::class, [
                 'class' => Users::class,
                 'choice_label' => function ($user) {
                    return $user->getNom() . ' ' . $user->getPrenom();}
                 
                 
             ])
            ->add('save', SubmitType::class, [
                'label'=> 'Envoyer'
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Possessions::class,
        ]);
    }
}
