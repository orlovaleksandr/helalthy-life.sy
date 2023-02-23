<?php

namespace App\Form;

use App\Entity\Product;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class EditProductFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Title (From class)',
                'required' => true,
                'constraints' => [
                    new NotBlank(message: 'Should be filled')
                ]
            ])
            ->add('price', NumberType::class, [
                'label' => 'Price (From class)',
                'scale' => 2,
                'html5' => true,
                'attr' => [
                    'step' => 0.01
                ]
            ])
            ->add('quantity', IntegerType::class)
            ->add('description', TextType::class)
            ->add('isPublished')
            ->add('isDeleted')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
