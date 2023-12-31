<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Validator\Constraints as Assert;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('fullName', TextType::class, [
                'attr' => [
                    'class' => 'form-control inputBox',
                    'placeholder' => 'Nom / Prénom',
                    'minlength' => '2',
                    'maxlength' => '50',
                ],
            ])
            ->add('pseudo', TextType::class, [
                'attr' => [
                    'class' => 'form-control inputBox',
                    'placeholder' => 'Pseudo',
                    'minlength' => '2',
                    'maxlength' => '50',
                ],
            ])
            ->add('email', EmailType::class, [
                'attr' => [
                    'class' => 'form-control inputBox',
                    'placeholder' => 'Adresse email',
                    'minlength' => '5',
                    'maxlength' => '180',
                ],
            ])
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'first_options' => [
                    'attr' => [
                        'class' => 'form-control inputBox',
                        'placeholder' => 'Mot de passe',
                        'minlength' => '6',
                        'maxlength' => '50',
                    ],
                ],
                'second_options' => [
                    'attr' => [
                        'class' => 'form-control inputBox',
                        'placeholder' => 'Confirmation du mot de passe',
                        'minlength' => '6',
                        'maxlength' => '50',
                    ],
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
