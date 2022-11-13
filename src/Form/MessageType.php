<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Message;
use App\Entity\Location;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class MessageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('recipient', HiddenType::class)
                ->add('text', TextareaType::class, [
                    // allows not to use bootstrap form template
                    "attr"=>[
                        "class"=>"form-control",
                    ],
                    "label"=>"Your message",
                ])
                ->add('send', SubmitType::class, [
                    "attr"=>[
                        "class"=>"button-small",
                    ],
                    "label"=>"Send"
                ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Message::class,
        ]);
    }
}
