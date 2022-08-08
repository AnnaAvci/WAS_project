<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\InstantMessage;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class MessageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('recipient', EntityType::class, [
            "class"=>User::class,
            "choice_label"=>"name_user",
            "attr"=>[
                "class"=>"form-control",
            ],
            "label"=>"To: "
        ])
            ->add('text', TextareaType::class, [
                // allows not to use bootstrap form template
                "attr"=>[
                    "class"=>"form-control",
                ],
                "label"=>"Your message",
            ])
       
            ->add('send', SubmitType::class, [
                "attr"=>[
                    "class"=>"btn btn-primary",
                ],
                "label"=>"Send"
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => InstantMessage::class,
        ]);
    }
}
