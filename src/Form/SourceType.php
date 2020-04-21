<?php

namespace App\Form;

use App\Entity\Source;
use App\Entity\Tag;
use App\Entity\User;
use App\Service\ParsersParameters;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SourceType extends AbstractType
{
    /**
     * @var ParsersParameters
     */
    private $parameters;

    public function __construct(ParsersParameters $parameters)
    {
        $this->parameters = $parameters;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('url', UrlType::class)
            ->add('parser', ChoiceType::class, [
                'choices' => $this->parameters->getParserChoices(),
            ])
            ->add('icon', UrlType::class)
            ->add('visibility', ChoiceType::class, [
                'choices' => Source::getChoices(),
            ])
            ->add('updateInterval')
            ->add('createdBy', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'name',
            ])
            ->add('tags', EntityType::class, [
                'class' => Tag::class,
                'choice_label' => 'name',
                'multiple' => true,
//                'expanded' => true,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Source::class,
            'attr' => ['id' => 'sourceForm'],
        ]);
    }
}
