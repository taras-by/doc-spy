<?php

namespace App\Form;

use App\Entity\Tag;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TagType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('isEnabled', ChoiceType::class, [
                'choices' => Tag::getEnableChoices(),
            ])
            ->add('favorite', ChoiceType::class, [
                'choices' => Tag::getFavoriteChoices(),
            ])
            ->add('order');
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Tag::class,
            'attr' => ['id' => 'tagForm'],
        ]);
    }
}
