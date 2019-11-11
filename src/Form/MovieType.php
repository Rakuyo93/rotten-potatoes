<?php

namespace App\Form;

use App\Entity\Movie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\Category;
use App\Entity\People;

class MovieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'label' => "Titre du titre",
                'attr' => ['placeholder' => "Donner un titre"]
            ])
            ->add('slug', TextType::class, [
                'label' => "Slug du film",
                'attr' => ['placeholder' => "Donner un slug au film"]
            ])
            ->add('poster', UrlType::class, [
                'label' => "Affiche du film",
                'attr' => ['placeholder' => "http://placehold.it/1120x200"]
            ])
            ->add('releaseAt', DateType::class, ['label' => "Date de sortie du film", 'widget' => 'single_text'])
            ->add('synopsis', TextareaType::class, [
                'label' => "Synopsis",
                'attr' => ['placeholder' => "Résumer en quelques mots le film"]
            ])
            ->add('categories', EntityType::class, [
                'label' => "Catégories",
                'class' => Category::class,
                'choice_label' => 'title',
                'multiple' => true,
                'expanded' => true
            ])
            ->add('actors', EntityType::class, [
                'label' => "Acteurs et actrices",
                'class' => People::class,
                'choice_label' => 'fullName',
                'multiple' => true
            ])
            ->add('director', EntityType::class, [
                'label' => "Réalisateurs et réalisatrices",
                'class' => People::class,
                'choice_label' => 'fullName'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Movie::class,
        ]);
    }
}
