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
            ->add('keywords', TextType::class, array(
                'label' => 'Entrez les mots clés à rechercher dans les titres',
                'required' => false
            ))
            ->add('contentToo', CheckboxType::class, array(
                'label' => 'Rechercher également dans le contenu ?',
                'required' => false
            ))
            ->add('IT', CheckboxType::class, array(
                'label' => 'Catégorie technologie',
                'required' => false
            ))
            ->add('Neuro', CheckboxType::class, array(
                'label' => 'Catégorie science',
                'required' => false
            ))
            ->add('Socio', CheckboxType::class, array(
                'label' => 'Catégorie sociologie',
                'required' => false
            ))
            ->add('Psycho', CheckboxType::class, array(
                'label' => 'Catégorie psychologie',
                'required' => false
            ))
            ->add('cinema', CheckboxType::class, array(
                'label' => 'Catégorie cinéma',
                'required' => false
            ))
            ->add('Autres', CheckboxType::class, array(
                'label' => 'Catégorie divers',
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
