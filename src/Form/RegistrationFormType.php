<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
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
            ->add('phone_user', TelType::class, ['label' => 'Phone number: '])
            ->add('email', EmailType::class, ['label' => 'Email: '])
            ->add('name_user', TextType::class, ['label' => 'Name: '])
            ->add('country_user', CountryType::class, ['label' => 'Country: '])
            ->add('city_user', TextType::class, ['label' => 'City: '])
            ->add('postcode_user', TextType::class, ['label' => 'Postcode: '])
            ->add('picture_user', FileType::class,array('data_class' => null),[
                'label' => 'Profile picture',
                'required' => false,
                
            ])
            ->add('plainPassword',RepeatedType::class, [
                    'mapped' => false,
                    'type' => PasswordType::class,
                    'invalid_message' => 'The password fields must match.',
                    'options' => ['attr' => ['class' => 'password-field']],
                    'required' => true,
                    'first_options'  => ['label' => 'Password'],
                    'second_options' => ['label' => 'Repeat Password'],
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