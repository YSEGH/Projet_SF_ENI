<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('lastname', TextType::class, [
                'label' => 'Nom',
                'attr' => ['placeholder' => 'Merci de saisir votre nom'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Merci de saisir votre nom',
                    ])
                ],
            ])
            ->add('firstname', TextType::class, [
                'label' => 'Prénom',
                'attr' => ['placeholder' => 'Merci de saisir votre prénom'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Merci de saisir votre prénom',
                    ])
                ],
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'attr' => ['placeholder' => 'Merci de saisir votre email'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Merci de saisir votre email',
                    ])
                ],
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Merci de saisir deux mots de passe identiques.',
                'label' => 'Mot de passe',
                'required' => true,
                'first_options' => [
                    'label' => 'Mot de passe',
                    'attr' => ['placeholder' => 'Merci de saisir votre mot de passe'],
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Merci de saisir votre mot de passe',
                        ]),
                        new Length([
                            'min' => 8,
                            'max' => 4096,
                            'minMessage' => 'Votre mot de passe doit contenir au minimum 8 caractères.'
                        ])
                    ],
                ],
                'second_options' => [
                    'label' => 'Confirmation du mot de passe',
                    'attr' => ['placeholder' => 'Merci de confirmer votre mot de passe'],
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Merci de saisir la confirmation du mot de passe',
                        ]),
                        new Length([
                            'min' => 8,
                            'max' => 4096,
                            'minMessage' => 'Votre mot de passe doit contenir au minimum 8 caractères.'
                        ])
                    ],
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'M\'inscrire',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
