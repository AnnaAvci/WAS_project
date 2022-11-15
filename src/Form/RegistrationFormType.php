<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class,[
                'label' => ''])
            ->add('first_name', TextType::class)
            ->add('picture_user', FileType::class,[
                'label' => 'Profile picture',
                'required' => false,
                'data_class' => null  
            ])
            ->add('plainPassword',RepeatedType::class, [
                    'mapped' => false,
                    'type' => PasswordType::class,
                    'invalid_message' => 'The password fields must match.',
                    'options' => ['attr' => ['class' => 'password-field']],
                    'required' => true,
                    'first_options'  => ['label' => 'Password'],
                    'second_options' => ['label' => 'Repeat Password'],
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Password field cannot be blank',
                        ]),
                        new Regex([
                            // Au moins une lettre de l'alphabet Latin en majuscule : (?=.*?[A-Z])
                            // Au moins une lettre de l'alphabet Latin en minuscule : (?=.*?[a-z])
                            // Au moins un chiffre : (?=.*?[0-9])
                            // Au moins un caractère spécial : (?=.*?[#?!@$%^&*-])
                            // Au moins 12 caractères : .{12,}
                            'pattern' => '/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{12,}$/',
                            'match' => true,
                            'message' => 'Your password must contain at least one capital letter, one lower-case letter, one number and one special character',
                        ]),
                        new Length([
                            'min' => 12,
                            'minMessage' => 'Your password must contain at least {{ limit }} characters',
                            // la longueur maximale autorisée par Symfony pour des raisons de sécurité est de 4096, cependant, certaines personnes recommandent de la mettre à 200
                            'max' => 200,
                            'maxMessage' => 'Your password must not exceed{{ limit }} characters',
                        ]),
                    ]
                ])
            ->add('rolePhotographer', CheckboxType::class, [
                'mapped' => false,
                'required' => false,
                'label' => 'I am a photographer'
            ])
            ->add('roleHost', CheckboxType::class, [
                'mapped' => false,
                'required' => false,
                'label' => 'I am a host'
            ])

            ->add('agreeTerms', CheckboxType::class, [
                 'mapped' => false,
                 'constraints' => [
                    new IsTrue([
                        'message' => 'You should agree to our terms.',
                    ]),
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
// 