<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\LoginFormType;
use App\Form\PasswordResetFormType;
use App\Repository\UserRepository;
use App\Service\UserService;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="login")
     * @param AuthenticationUtils $authenticationUtils
     * @return Response
     */
    public function login(AuthenticationUtils $authenticationUtils)
    {
        if ($error = $authenticationUtils->getLastAuthenticationError()) {
            $this->addFlash('danger', $error->getMessageKey());
            return $this->redirectToRoute('login');
        }

        $form = $this->createForm(LoginFormType::class);

        return $this->render('security/login.html.twig', [
            'loginForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/reset-password", name="reset_password")
     * @param Request $request
     * @param UserService $userService
     * @param UserRepository $userRepository
     * @return Response
     * @throws Exception
     */
    public function resetPassword(Request $request, UserService $userService, UserRepository $userRepository)
    {
        $form = $this->createForm(PasswordResetFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $email = $form->getData()->getEmail();
            /** @var User $user */
            $user = $userRepository->findOneBy(['email' => $email]);

            $userService->resetPassword($user);
            $this->addFlash('success', 'Password sent to email!');
            return $this->redirectToRoute('reset_password');
        }

        foreach ($form->getErrors(true) as $error) {
            $this->addFlash('danger', $error->getMessage());
        }

        return $this->render('security/reset_password.html.twig', [
            'passwordResetForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/logout", name="logout")
     * @throws Exception
     */
    public function logout()
    {
        throw new Exception('will be intercepted before getting here.');
    }
}
