<?php

namespace App\Form;

use App\Entity\Article;
use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titre', TextType::class, ['label' => 'Titre :'])
            ->add('category', EntityType::class, array(
                'class' => Category::class,
                'choice_label' => 'Name'))
            ->add('content', TextareaType::class, ['label' => 'Contenu :', 'attr' => ['cols' => 50, 'rows' => 10, 'class' => 'ckeditor']])
            ->add('weight', ChoiceType::class, array(
                'choices' => array('Mineure' => 0, 'Moyenne' => 1, 'Majeure' => 2), 'label' => 'Importance :'))
            ->add('image', UrlType::class, ['label' => 'Image-accroche (URL) :']);
    }   

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
