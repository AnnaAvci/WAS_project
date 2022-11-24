<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Location;
use App\Entity\PhotoService;
use App\Entity\PhotoLocation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;

class LocationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
          
            ->add('name_location', TextType::class,['label' => 'Headline: '])
            ->add('country_location', CountryType::class, ['label' => 'Country: '])
            ->add('city_location', TextType::class, ['label' => 'City: '])
            ->add('postcode_location', TextType::class, ['label' => 'Postcode: '])
            ->add('price_location', NumberType::class, ['label' => 'Price per hour: '])
            // add photos; not imported to database, so mapped=>false
            ->add('photoLocation', FileType::class, [
                // looks for choices from this entity
                'label'=>'Photos of your property',
                'multiple'=>true,
                'mapped'=>false,
                'required'=>false,
        /*          'constraints' => [
                    new Image([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'application/jpeg',
                            'application/jpg',
                            'application/webp',
                            'application/png',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid jpg, jpeg, png or webp document',
                    ])
                ]  */   
             ])
            
            ->add('Submit', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Location::class,
        ]);
    }
}
