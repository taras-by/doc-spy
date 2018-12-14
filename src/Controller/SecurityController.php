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
        $lastUsername = $authenticationUtils->getLastUsername();
        if($error = $authenticationUtils->getLastAuthenticationError()){
            $this->addFlash('danger', $error->getMessageKey());
        }
        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
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
        $email = $request->get('email');
        $user = $this->getUser();
        $defaultEmail = $user ? $user->getEmail() : null;

        if ($request->isMethod(Request::METHOD_POST)) {
            $user = $user ? $user : $this->getDoctrine()->getRepository(User::class)->findOneBy(['email' => $email]);
            if ($user) {
                $userService->resetPassword($user);
                $this->addFlash('success', 'Password sent to email!');
            } else {
                $this->addFlash('danger', 'User not found!');
            }
            return $this->redirectToRoute('reset_password');
        }

        return $this->render('security/reset_password.html.twig', [
            'defaultEmail' => $defaultEmail,
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
