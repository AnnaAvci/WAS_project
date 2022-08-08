<?php

namespace App\Form;

use App\Data\SearchServiceData;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;

class SearchServiceForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('q', TextType::class, [
            'label' => false,
            'required' => false,
            'attr' => ['placeholder' => 'Find a photoshoot...']
        ])
        ->add('min', NumberType::class, [
            'html5' => true,
            'label' => false,
            'required' => false,
            'attr' => ['placeholder' => 'Min Price...', 'min' => 0]
        ])
        ->add('max', NumberType::class, [
            'html5' => true,
            'label' => false,
            'required' => false,
            'attr' => ['placeholder' => 'Max Price...', 'min' => 0]
        ])
        ->add('countryService', CountryType::class, [
            'label' => false,
            'alpha3' => true,
            'required' => false,
            'placeholder' => 'Country',
        ])
    
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' =>SearchServiceData::class,
            'method' =>'GET',
            'csrf_protection' => false,
        ]);
    }

     // put nothing to keep the url simple
     public function getBlockPrefix()
     {
         return '';
     }
}
