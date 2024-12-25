<?php

namespace App\Form;

use App\Entity\Projects;
use App\Entity\Sources;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SourceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('cle', TextType::class, [
                'label' => 'Clé',
                'attr' => ['placeholder' => 'Veuillez entrer une clé'],
            ])
            ->add('content', TextareaType::class, [
                'label' => 'Contenu',
                'attr' => ['placeholder' => 'Veuillez entrer un contenu'],
            ])
            ->add('project', EntityType::class, [
                'class' => Projects::class,
                'choice_label' => 'name',
                'label' => 'Projet lié',
                'placeholder' => 'Choisir un projet',
                'data' => $options['project'] ?? null,
                'disabled' => $options['project'] !== null,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Sources::class,
            'project' => null,
        ]);
    }
}
