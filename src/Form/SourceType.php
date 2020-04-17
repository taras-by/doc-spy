<?php

namespace App\Form;

use App\Entity\Source;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SourceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('url')
            ->add('parser')
            ->add('icon')
            ->add('visibility')
            ->add('updateInterval')
//            ->add('updatedAt')
//            ->add('errorCount')
//            ->add('scheduleAt')
//            ->add('tags')
//            ->add('createdBy')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Source::class,
            'attr' => ['id' => 'sourceForm'],
        ]);
    }
}
