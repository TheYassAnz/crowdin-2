<?php

namespace App\Form;

use App\Entity\Language;
use App\Entity\Projects;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bundle\SecurityBundle\Security;

class ProjectType extends AbstractType
{
    public function __construct(
        private Security $security
    ) {}

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $currentUser = $this->security->getUser();

        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom du projet',
            ])
            ->add('user', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'email',
                'label' => 'Propriétaire',
                'data' => $currentUser,
                'disabled' => true,
            ])
            ->add('collaborator', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'email',
                'label' => 'Collaborateur',
                'required' => false,
                // 'mapped' => false,
            ])
            ->add('start_language', EntityType::class, [
                'class' => Language::class,
                'choice_label' => 'name',
            ])
            ->add('target_language', EntityType::class, [
                'class' => Language::class,
                'choice_label' => 'name',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Projects::class,
        ]);
    }
}
