<?php

namespace App\Form;

use App\Entity\Recipe;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;


class AddRecipeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class , array(
                'label' => false,
            ))
            ->add('description', TextareaType::class, array(
                'label' => false,
            ))
            ->add('instruction', TextareaType::class, array(
                'label' => false,

            ))
            ->add('preparationTime', IntegerType::class, array(
                'label' => false,
                
            ))
            ->add('level', ChoiceType::class, array(
                'label' => false,
                'choices'   => array(
                    'Niveau'   => 0,
                    '1'   => 1,
                    '2'   => 2,
                    '3'   => 3,
                    '4'   => 4 ,
                    '5'   => 5 

                ),
                'multiple'  => false,
            ))
            ->add('pictures', FileType::class, array(
                'label' => false,
            ))
            ->add('totalPrice', MoneyType::class, array(
                'label' => false,
                'currency' => false,
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Recipe::class,
        ]);
    }
}
