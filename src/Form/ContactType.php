<?php

namespace App\Form;

use App\Entity\ContactMessage;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class, ['label' => 'Nom * :',
            'required' => true])
            ->add('prenom', TextType::class, ['label' => 'Prénom* :',
            'required' => false])
            ->add('email', EmailType::class, ['label' => 'E-mail * :',
            'required' => true])
            ->add('compagnie', TextType::class, ['label' => 'Compagnie :',
            'required' => false])
            ->add('phone', TelType::class, ['label' => 'Numéro de téléphone :',
            'required' => false])
            ->add('sujet', TextType::class, ['label' => 'Sujet de votre mail* :',
            'required' => true])
            ->add('contenu', TextareaType::class, ['label' => 'Contenu du mail à envoyer * :', 'attr' => ['cols' => 50, 'rows' => 10, 'class' => 'ckeditor'],
            'required' => true])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ContactMessage::class,
        ]);
    }
}
