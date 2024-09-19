<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterForm;
use App\Service\UserManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AuthController extends AbstractController
{
    public function __construct(
        private readonly UserManager $userManager
    )
    {
    }

    #[Route('/register', name: 'register', methods: ['GET', 'POST'])]
    public function register(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(RegisterForm::class, $user, [
            'method' => 'POST',
            'action' => $this->generateUrl('register')
        ]);

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $plainPassword = $form['password']->getData();
                $this->userManager->processNewUser($user, $plainPassword);

                $user->setLastLogin(new \DateTime());
                $user->setCreatedAt(new \DateTime());
                $entityManager->persist($user);
                $entityManager->flush();
                //$this->flashMessageHelper->addFormSuccessAsFlash("Bienvenue sur the Feed");
                return $this->redirectToRoute('app_home');
            }
        }

        // $this->flashMessageHelper->addFormErrorsAsFlash($form);

        return $this->render('auth/register.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/login', name: 'login', methods: ['GET', 'POST'])]
    public function connexion(Request $request, AuthenticationUtils $authenticationUtils): Response
    {
        $lastUsername = $authenticationUtils->getLastUsername();
        return $this->render('auth/login.html.twig', [
            'last_username' => $lastUsername,
        ]);
    }
}
