<?php

namespace App\Form;

use App\Entity\Source;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SourceType extends AbstractType
{
    /**
     * @var ParameterBagInterface
     */
    private $parameterBag;

    public function __construct(ParameterBagInterface $parameterBag)
    {
        $this->parameterBag = $parameterBag;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('url')
            ->add('parser', ChoiceType::class, [
                'choices' => $this->getParserChoices(),
            ])
            ->add('icon', UrlType::class)
            ->add('visibility', ChoiceType::class, [
                'choices' => Source::getChoices(),
            ])
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

    /**
     * @return array
     */
    private function getParsers(): array
    {
        $parsers = $this->parameterBag->get('parsers');
        if ($this->parameterBag->has('custom_parsers')) {
            $parsers = $parsers + $this->parameterBag->get('custom_parsers');
        }
        return $parsers;
    }

    /**
     * @return array
     */
    private function getParserChoices(): array
    {
        return array_flip($this->getParsers());
    }
}
