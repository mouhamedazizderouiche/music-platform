<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Nom', TextType::class, [
                'label' => 'Nom',
                'attr' => ['placeholder' => 'Nom'],
            ])
            ->add('Prenom', TextType::class, [
                'label' => 'Prénom',
                'attr' => ['placeholder' => 'Prénom'],
            ])
            ->add('pseudo', TextType::class, [
                'label' => 'Pseudo',
                'attr' => ['placeholder' => 'Pseudo'],
            ])
            ->add('Email', EmailType::class, [
                'label' => 'Adresse email',
                'attr' => ['placeholder' => 'Adresse email'],
            ])
            ->add('NumTelephone', TelType::class, [
                'label' => 'Numéro de téléphone',
                'attr' => ['placeholder' => 'Numéro de téléphone'],
                'required' => false,
            ])
            ->add('Pays', ChoiceType::class, [
                'label' => 'Pays',
                'choices' => [
                    'Choisir un pays' => '',
                    'Afghanistan' => 'AF',
                    'Albanie' => 'AL',
                    'Algérie' => 'DZ',
                    'France' => 'FR',
                    // Ajoutez les autres pays comme dans votre HTML
                    'Zimbabwe' => 'ZW',
                ],
                'attr' => ['class' => 'form-select'],
            ])
            ->add('motdepasse', PasswordType::class, [
                'label' => 'Mot de passe',
                'attr' => ['placeholder' => 'Mot de passe'],
            ])
            ->add('confmotdepasse', PasswordType::class, [
                'label' => 'Confirmer mot de passe',
                'attr' => ['placeholder' => 'Confirmer mot de passe'],
                'mapped' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}