<?php

namespace App\Service;

use App\Entity\User;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserService
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    /**
     * @var NotificationService
     */
    private $notificationService;

    /**
     * @var RegistryInterface
     */
    private $entityManager;


    public function __construct(
        UserPasswordEncoderInterface $passwordEncoder,
        NotificationService $notificationService,
        RegistryInterface $entityManager
    )
    {
        $this->passwordEncoder = $passwordEncoder;
        $this->notificationService = $notificationService;
        $this->entityManager = $entityManager;
    }

    /**
     * @param User $user
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Exception
     */
    public function resetPassword(User $user)
    {
        $password = $this->generatePassword();
        $encoded = $this->passwordEncoder->encodePassword($user, $password);
        $user->setPassword($encoded);
        $em = $this->entityManager->getEntityManager();
        $em->persist($user);
        $em->flush();

        $this->notificationService->send(
            $user,
            'Your password has been changed',
            'mail/password_reset.html.twig',
            [
                'password' => $password,
            ]
        );
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function generatePassword(): string
    {
        return substr(md5(random_bytes(10)), 0, 8);
    }
}