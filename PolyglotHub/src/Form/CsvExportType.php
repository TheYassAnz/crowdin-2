<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use App\Form\EnumType;
use App\Enum\CsvFormat;

class CsvExportType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('format', EnumType::class, [
                'enum_class' => CsvFormat::class,
                'placeholder' => 'Choose a format',
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Export',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([]);
    }
}