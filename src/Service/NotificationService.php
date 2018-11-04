<?php

namespace App\Service;

use App\Entity\Item;

class NotificationService
{
    private $mailer;
    private $twig;
    private $from;

    public function __construct($mailer, $twig, $from)
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
        $this->from = $from;
    }

    public function send($user, $subject, $template, $parameters = [])
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
