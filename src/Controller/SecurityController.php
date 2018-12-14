<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="login")
     * @param AuthenticationUtils $authenticationUtils
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function login(AuthenticationUtils $authenticationUtils)
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();
        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error'         => $error,
        ]);
    }

    /**
     * @Route("/reset-password", name="reset_password")
     * @param Request $request
     * @param UserService $userService
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function resetPassword(Request $request, UserService $userService)
    {
        $error = null;
        $message = null;
        $email = $request->get('email');
        $user = $this->getUser();
        $defaultEmail = $user ? $user->getEmail() : null;

        if ($request->isMethod(Request::METHOD_POST)) {
            $user = $user ? $user : $this->getDoctrine()->getRepository(User::class)->findOneBy(['email' => $email]);
            if ($user) {
                $userService->resetPassword($user);
                $message = 'Password sent to email!';
            } else {
                $error = 'User not found!';
            }
        }

        return $this->render('security/reset_password.html.twig', [
            'defaultEmail' => $defaultEmail,
            'message' => $message,
            'error' => $error,
        ]);
    }

    /**
     * @Route("/logout", name="logout")
     * @throws \Exception
     */
    public function logout()
    {
        throw new \Exception('will be intercepted before getting here.');
    }
}
