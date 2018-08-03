<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class RegisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('login', TextType::class, ['label' => 'Nom d\'utilisateur :'])
            ->add('password', PasswordType::class, ['label' => 'Mot de passe :'])
            ->add('confirm_password', PasswordType::class, ['label' => 'Confirmez votre mot de passe :'])
            ->add('email', TextType::class, ['label' => 'E-mail :'])
            ->add('confirm_email', TextType::class, ['label' => 'Confirmez votre e-mail :'])
            ->add('newsletter_subscriber', TextType::class, ['label' => 'Souscrire à la newsletter ?'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
