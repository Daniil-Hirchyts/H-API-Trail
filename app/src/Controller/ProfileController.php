<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ProfileForm;
use App\Service\UserManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class ProfileController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly Security               $security,
        private readonly UserManager            $userManager
    )
    {
    }

    #[Route('/profile/edit', name: 'profile_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request): Response
    {
        $user = $this->security->getUser();

        // Vérifier si l'utilisateur est connecté
        if (!$user instanceof User) {
            throw new AccessDeniedException('Vous devez être connecté pour accéder à cette page.');
        }

        $form = $this->createForm(ProfileForm::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $plainPassword = $form->get('password')->getData();
            $confirmPassword = $form->get('confirmPassword')->getData();

            // Vérifier la confirmation du mot de passe
            if ($plainPassword && $plainPassword !== $confirmPassword) {
                $form
                    ->get('confirmPassword')
                    ->addError(new FormError('Les mots de passe ne correspondent pas.'));
            } else {
                // Mettre à jour le mot de passe si un nouveau mot de passe est défini
                if ($plainPassword) {
                    $user->setPassword(
                        $this->userManager->encodePassword($user, $plainPassword)
                    );
                }

                $this->entityManager->flush();

                $this->addFlash('success', 'Profil mis à jour avec succès.');

                return $this->redirectToRoute('profile_edit');
            }
        }

        return $this->render('profile/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
