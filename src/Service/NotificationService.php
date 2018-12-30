<?php

namespace App\Service;

use App\Entity\User;
use Swift_Mailer;
use Twig\Environment;

class NotificationService
{
    /**
     * @var Swift_Mailer
     */
    private $mailer;

    /**
     * @var Environment
     */
    private $twig;

    /**
     * @var string
     */
    private $from;

    public function __construct(Swift_Mailer $mailer, Environment $twig, string $from)
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
        $this->from = $from;
    }

    /**
     * @param User $user
     * @param string $subject
     * @param string $template
     * @param array $parameters
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function send(User $user, string $subject, string $template, array $parameters = [])
    {
        $message = (new \Swift_Message($subject))
        ->setFrom($this->from)
        ->setTo($user->getEmail())
        ->setBody(
            $this->twig->render(
                $template,
                array_merge(['name' => $user->getName()], $parameters)
            ),
            'text/html'
        );

        $this->mailer->send($message);
    }
}
