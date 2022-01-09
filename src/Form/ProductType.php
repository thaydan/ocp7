<?php

namespace App\Form;

use App\Entity\Product;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('brand')
            ->add('name')
            ->add('OS')
            ->add('storage')
            ->add('RAM')
            ->add('screenSize')
            ->add('weight')
            ->add('length')
            ->add('width')
            ->add('height')
            ->add('battery')
            ->add('connectivity')
            ->add('microSD')
            ->add('color')
            ->add('createdAt')
            ->add('updatedAt')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
