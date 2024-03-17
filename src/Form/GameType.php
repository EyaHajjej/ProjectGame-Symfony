<?php

namespace App\Form;
use App\Entity\Game;

use App\Entity\Job;
use App\Entity\Image;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class GameType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre', TextType::class, ['attr' => ['class' => 'form-control  '],])
            ->add('type',  TextType::class,['attr' => ['class' => 'form-control  '],])
            ->add('nbrJoueur',  TextType::class,[
                'attr' => ['class' => 'form-control'],
                ])
            ->add('Editeur',  TextType::class,[
                'attr' => ['class' => 'form-control  '],
                ])
            ->add('image')
            ->add('image', EntityType:: class,['attr' => ['class' => 'form-control  '], 
                'class' => Image:: class,
                'choice_label' => 'url', 
                ])
            ->add('Valider', SubmitType:: class, [
                'attr' => ['class' => 'btn btn-primary'],
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
