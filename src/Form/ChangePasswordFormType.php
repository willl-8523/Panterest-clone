<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class ChangePasswordFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // dd($options);

        /*  si current_password_is_required => true alors ajoute le champ
            currentPassWord
        */
        if ($options['current_password_is_required']) {
            $builder
                ->add('currentPassword', PasswordType::class, [
                    'label' => 'Current Password',
                    'attr' => [
                        'autocomplete' => 'off',
                    ],
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Please enter your current Password',
                        ]),
                        // Verifie si le mdp entrer = mdp courant
                        new UserPassword(['message' => 'Invalid current password.']),
                    ]
                ]);
        }

        $builder
            ->add('newPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'first_options' => [
                    'attr' => ['autocomplete' => 'new-password'],
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Please enter a password',
                        ]),
                        new Length([
                            'min' => 6,
                            'minMessage' => 'Your password should be at least {{ limit }} characters',
                            // max length allowed by Symfony for security reasons
                            'max' => 4096,
                        ]),
                    ],
                    'label' => 'New Password',
                ],
                'second_options' => [
                    'attr' => ['autocomplete' => 'new-password'],
                    'label' => 'Confirm Your New Password',
                ],
                'invalid_message' => 'The password fields must match.',
                // Instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        /* On configure a false par defaut */
        $resolver->setDefaults([
            'current_password_is_required' => false,
        ]);

        /*  Typer current_password_is_required
            Voir https://symfony.com/doc/5.4/components/options_resolver.html#value-validation => types validation
        */
        $resolver->setAllowedTypes('current_password_is_required', 'bool');

        /* Autoriser +sieurs types 
           $resolver->setAllowedTypes('current_password_is_required', ['bool', 'string', ..]) 
        */
    }
}
