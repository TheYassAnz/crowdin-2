<?php

namespace App\Form;

use App\Entity\Projects;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotBlank;

class CsvImportType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('file', FileType::class, [
                'label' => 'CSV File',
                'constraints' => [
                    new NotBlank(),
                    new File([
                        'mimeTypes' => [
                            'text/csv',
                            'text/plain',
                            'application/vnd.ms-excel',
                            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                        ]
                    ])
                ]
            ])
            ->add('project', EntityType::class, [
                'class' => Projects::class,
                'choice_label' => 'name',
                'label' => 'Project'
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Import'
            ])
        ;
    }
}
