<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use UnitEnum;

class EnumType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setRequired(['enum_class']);
        $resolver->setDefaults([
            'choices' => [],
            'choice_label' => fn (UnitEnum $enum) => $enum->value,
        ]);

        $resolver->setNormalizer('choices', function ($options) {
            return $options['enum_class']::cases();
        });
    }

    public function getParent(): string
    {
        return ChoiceType::class;
    }
}
