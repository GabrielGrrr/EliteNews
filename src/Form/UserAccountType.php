<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class UserAccountType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('login', TextType::class, ['label' => 'Nom d\'utilisateur :', 'required' => true])
            ->add('avatar', UrlType::class, ['label' => 'Avatar (URL) :', 'required' => false])
            ->add('subtitle', TextType::class, ['label' => 'Courte description :', 'required' => false])
            ->add('signature', TextType::class, ['label' => 'Signature :', 'required' => false])
            ->add('localisation', TextType::class, ['label' => 'Lieu/Ville :', 'required' => false])
            ->add('newsletter_subscriber', CheckboxType::class, ['label' => 'Souscrire ) la newsletter :', 'required' => false])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
