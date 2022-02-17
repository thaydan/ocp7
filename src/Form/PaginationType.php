<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints\LessThanOrEqual;

class PaginationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('page', NumberType::class, [
                'constraints' => [
                    new GreaterThanOrEqual(0),
                ],
                'required' => false,
                'empty_data' => "0"
            ])
            ->add('nbElementsPerPage', NumberType::class, [
                'constraints' => [
                    new GreaterThanOrEqual(5),
                    new LessThanOrEqual(40)
                ],
                'required' => false,
                'empty_data' => "20"
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([]);
    }
}
