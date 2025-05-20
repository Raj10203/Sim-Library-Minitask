<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', TextType::class, [
                'attr' => [
                    'placeholder' => 'Email',
                    'class' => 'form-control',
                    'autofocus' => true,
                ]
            ])
            ->add('firstName', TextType::class, [
                'attr' => [
                    'placeholder' => 'First Name',
                    'class' => 'form-control',
                    'autofocus' => true,
                ]
            ])
            ->add('lastName', TextType::class, [
                'attr' => [
                    'placeholder' => 'Last Name',
                    'class' => 'form-control',
                    'autofocus' => true,
                ]
            ])
            ->add('roles', ChoiceType::class, [
                'choices' => [
                    'Main Roles' => [
                        'Admin' => 'ROLE_ADMIN',
                    ],
                    'Sub Roles' => [
                        'Librarian' => 'ROLE_LIBRARIAN',
                        'Normal User' => 'ROLE_USER',
                    ]

                ],
                'multiple' => true,
                'required' => true,
                'label' => 'Roles',
                'attr' => [
                    'class' => 'select2-dropdown-multiple',
                ]
            ])
            ->add('active', ChoiceType::class, [
                'choices' => [
                    'Active' => true,
                    'Inactive' => false,
                ],
                'expanded' => false,
                'multiple' => false,
                'label' => 'Status',
                'required' => true,
                'attr' => [
                    'class' => 'select2-dropdown-single',
                ]
            ])
            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'attr' => [
                    'autocomplete' => 'new-password',
                    'class' => 'form-control',
                    'placeholder' => 'Password',
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 64,
                    ]),
                ],
            ]);
    }
//<input type="checkbox" class="form-check-input" checked="">
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'csrf_token_id' => 'user_token',
        ]);
    }
}
