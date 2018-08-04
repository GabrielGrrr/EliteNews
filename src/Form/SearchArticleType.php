<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class SearchArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('keyword', TextType::class, array(
                'label' => 'Mots-clés :',
                'required' => false
            ))
            ->add('contentToo', CheckboxType::class, array(
                'label' => 'Inclure le contenu ?',
                'required' => false
            ))
            ->add('IT', CheckboxType::class, array(
                'label' => 'Technologie',
                'required' => false
            ))
            ->add('Neuro', CheckboxType::class, array(
                'label' => 'Science',
                'required' => false
            ))
            ->add('Socio', CheckboxType::class, array(
                'label' => 'Sociologie',
                'required' => false
            ))
            ->add('Psycho', CheckboxType::class, array(
                'label' => 'Psychologie',
                'required' => false
            ))
            ->add('cinema', CheckboxType::class, array(
                'label' => 'Cinéma',
                'required' => false
            ))
            ->add('Autres', CheckboxType::class, array(
                'label' => 'Divers',
                'required' => false
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
