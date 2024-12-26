<?php

namespace App\Form;

use App\Entity\Language;
use App\Entity\Sources;
use App\Entity\Translations;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TranslationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('source', EntityType::class, [
                'class' => Sources::class,
                'choice_label' => 'content',
                'label' => 'Source',
                'placeholder' => 'Choisir une source',
                'data' => $options['source'] ?? null,
                'disabled' => $options['source'] !== null,
            ])

            ->add('target_language', EntityType::class, [
                'class' => Language::class,
                'choice_label' => 'name',
                'label' => 'Langue cible',
                'placeholder' => 'Choisir une langue',
                'choices' =>  $options['source'] ? $options['source']->getProject()->getTargetLanguages() : null,
            ])

            ->add(
                'translated_content',
                TextareaType::class,
                ['label' => 'Contenu traduit']
            );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Translations::class,
            'source' => null, // Ajouter cette ligne pour définir l'option "source"
            'translation' => null,
        ]);
    }
}
