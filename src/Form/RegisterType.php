<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;

class RegisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => "Votre email",
                'attr' => ['placeholder' => "Ex: jean.douille@gmail.com"]
            ])
            ->add('password', PasswordType::class, [
                'label' => "Votre mot de passe",
                'attr' => ['placeholder' => "Avec au moins 8 caractères"]
            ])
            ->add('name', TextType::class, [
                'label' => "Votre pseudo",
                'attr' => ['placeholder' => "Ex: JeanDouille"]
            ])
            ->add('avatar', UrlType::class, [
                'label' => "Votre avatar",
                'default_protocol' => null,
                'attr' => ['placeholder' => "Ex: http://placehold.it/120x200"]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
