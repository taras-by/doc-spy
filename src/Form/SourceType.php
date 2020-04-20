<?php

namespace App\Form;

use App\Entity\Source;
use App\Service\ParsersParameters;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;

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
