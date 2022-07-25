<?php

namespace App\Form;

use App\Entity\Service;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;

class ServiceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name_service', TextType::class, ['label' => 'Photoshoot title: '])
            ->add('country_service', CountryType::class, ['label' => 'Country: '])
            ->add('city_service', TextType::class, ['label' => 'City: '])
            ->add('postcode_service', TextType::class, ['label' => 'Postcode: '])
            ->add('price_service', NumberType::class, ['label' => 'Price per hour: '])
              // add photos; not imported to database, so mapped=>false
            ->add('photoService', FileType::class, [
                // looks for choices from this entity
                'label'=>'Show us some of your work',
                'multiple'=>true,
                'mapped'=>false,
                'required'=>false,
            ])
            ->add('Submit', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Service::class,
        ]);
    }
}
