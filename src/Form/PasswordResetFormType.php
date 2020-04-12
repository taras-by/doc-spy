<?php

namespace App\Form;

use App\Entity\User;
use App\Traits\EntityManagerTrait;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class PasswordResetFormType extends AbstractType
{
    use EntityManagerTrait;

    /**
     * @var ContainerInterface
     */
    private $container;

    public function __construct(ContainerInterface $container, EntityManagerInterface $entityManager)
    {
        $this->container = $container;
        $this->entityManager = $entityManager;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $user = $this->getUser();
        $em = $this->entityManager;

        $builder->add('email', EmailType::class, [
            'constraints' => [
                new Callback(function ($value, ExecutionContextInterface $context) use ($em) {
                    if ($em->getRepository(User::class)->findOneBy(['email' => $value]) == null) {
                        $context
                            ->buildViolation('Email not found')
                            ->addViolation();
                    }
                }),
            ],
            'data' => $user ? $user->getEmail() : '',
            'attr' => ['class' => 'mb-3', 'placeholder' => 'Email address', 'autofocus' => true],
            'label' => 'Email address',
            'label_attr' => ['class' => 'sr-only'],
        ]);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'csrf_protection' => true,
            'csrf_token_id' => 'reset_password',
        ]);
    }

    /**
     * @return User|null
     */
    private function getUser()
    {
        if (null === $token = $this->container->get('security.token_storage')->getToken()) {
            return null;
        }

        if (!\is_object($user = $token->getUser())) {
            // e.g. anonymous authentication
            return null;
        }

        return $user;
    }
}
