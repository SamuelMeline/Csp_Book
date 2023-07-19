<?php

namespace App\Form;

use App\Entity\Build;
use App\Entity\Item;
use Symfony\Component\Form\AbstractType;
// use phpDocumentor\Reflection\Types\Integer;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
// use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\RangeType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;


class BuildType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // $isEdit = $options['is_edit'];

        $builder
            // ->add('image', FileType::class, [
            //     'required' => false,
            //     'mapped' => $isEdit,
            //     'constraints' => [
            //         new Assert\Image([
            //             'maxSize' => '5M',
            //             'maxSizeMessage' => 'La taille de l\'image ne doit pas dépasser 5 Mo.',
            //             'mimeTypes' => [
            //                 'image/jpeg',
            //                 'image/png',
            //                 'image/gif',
            //             ],
            //             'mimeTypesMessage' => 'Le format de l\'image n\'est pas valide. Les formats acceptés sont : JPG, PNG et GIF.',
            //         ]),
            //     ],
            // ])
            ->add('name', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'minlength' => '2',
                    'maxlength' => '50',
                ],
                'label' => 'Nom',
                'label_attr' => [
                    'class' => 'form-label'
                ],
                'constraints' => [
                    new Assert\Length(['min' => 2, 'max' => 50]),
                    new Assert\NotBlank()
                ]
            ])
            ->add('difficulty', RangeType::class, [
                'attr' => [
                    'class' => 'form-range',
                    'min' => 1,
                    'max' => 5,
                ],
                'label' => 'Difficulté',
                'label_attr' => [
                    'class' => 'form-label'
                ],
                'constraints' => [
                    new Assert\PositiveOrZero(),
                    new Assert\NotBlank()
                ]
            ])
            ->add('description', TextareaType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'min' => 1,
                    'max' => 5,
                ],
                'label' => 'Description',
                'label_attr' => [
                    'class' => 'form-label'
                ],
                'constraints' => [
                    new Assert\NotBlank()
                ]
            ])
            ->add('price', NumberType::class, [
                'attr' => [
                    'class' => 'form-control',
                ],
                'label' => 'Prix',
                'label_attr' => [
                    'class' => 'form-label'
                ],
                'constraints' => [
                    new Assert\PositiveOrZero(),
                ]
            ])
            ->add('isFavorite', CheckboxType::class, [
                'attr' => [
                    'class' => 'form-check-input',
                ],
                'label' => 'Favoris',
                'label_attr' => [
                    'class' => 'form-check-label'
                ],
                'required' => false,
            ])
            ->add('items', EntityType::class, [
                'class' => Item::class,
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' => true,
                'label' => 'Items',
                'label_attr' => [
                    'class' => 'form-label'
                ],
                'attr' => [
                    'class' => 'form-check-input',
                ],
                'required' => false,
            ])
            ->add('submit', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-form'
                ],
                'label' => 'Créer ma Panoplie'
            ]);
        }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Build::class,
            // 'is_edit' => true
        ]);
    }
}
