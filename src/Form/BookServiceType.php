<?php

namespace App\Form;

use App\Entity\BookService;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class BookServiceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('message', TextareaType::class)
        ->add('dateStart', DateType::class,[
            // displays a calendar to pick a date
            'widget' => 'single_text',
            'data' => new \DateTime()
        ])
        ->add('dateEnd', DateType::class,[
            // displays a calendar to pick a date
            'widget' => 'single_text',
            'data' => new \DateTime()
        ])
        ->add('Submit', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => BookService::class,
        ]);
    }
}
