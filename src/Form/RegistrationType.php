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
                    'class' => 'form-control',
                    'minlength' => '2',
                    'maxlength' => '50'
                ],
                'label' => 'Nom / Prénom',
                'label_attr' => [
                    'class' => 'form-label'
                ],
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Veuillez renseigner votre nom et prénom'
                    ]),
                    new Assert\Length([
                        'min' => 2,
                        'max' => 50,
                        'minMessage' => 'Votre nom et prénom doivent contenir au moins {{ limit }} caractères',
                        'maxMessage' => 'Votre nom et prénom doivent contenir au maximum {{ limit }} caractères'
                    ])
                ]
            ])
            ->add('pseudo', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'minlength' => '2',
                    'maxlength' => '50'
                ],
                'label' => 'Pseudo',
                'label_attr' => [
                    'class' => 'form-label'
                ],
                'constraints' => [
                    new Assert\Length([
                        'min' => 2,
                        'max' => 50,
                        'minMessage' => 'Votre pseudo doit contenir au moins {{ limit }} caractères',
                        'maxMessage' => 'Votre pseudo doit contenir au maximum {{ limit }} caractères',
                    ])
                ]
            ])
            ->add('email', EmailType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'minlength' => '2',
                    'maxlength' => '180'
                ],
                'label' => 'Adresse email',
                'label_attr' => [
                    'class' => 'form-label'
                ],
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Veuillez renseigner votre adresse email'
                    ]),
                    new Assert\Email([
                        'message' => 'Veuillez renseigner une adresse email valide'
                    ]),
                    new Assert\Length([
                        'max' => 180,
                        'maxMessage' => 'Votre adresse email doit contenir au maximum {{ limit }} caractères'
                    ])
                ]
            ])
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'first_options' => [
                    'attr' => [
                        'class' => 'form-control',
                        'minlength' => '8',
                        'maxlength' => '50'
                    ],
                    'label' => 'Mot de passe',
                    'label_attr' => [
                        'class' => 'form-label'
                    ],
                ],
                'second_options' => [
                    'attr' => [
                        'class' => 'form-control',
                        'minlength' => '8',
                        'maxlength' => '50'
                    ],
                    'label' => 'Confirmation du mot de passe',
                    'label_attr' => [
                        'class' => 'form-label'
                    ],
                ],
                'invalid_message' => 'Les mots de passe ne correspondent pas',
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'S\'inscrire',
                'attr' => [
                    'class' => 'btn btn-primary btn-block'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
