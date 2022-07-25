<?php

namespace App\Form;

use App\Data\SearchData;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;

class SearchForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {  
        $builder
            ->add('q', TextType::class, [
                'label' => false,
                'required' => false,
                'attr' => ['placeholder' => 'Search...']
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
            ->add('countryLocation', CountryType::class, [
                'label' => false,
                'alpha3' => true,
                'data' => 'FRA'
            ])
            ;
    }

    // what is used by default in the search bar
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' =>SearchData::class,
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
