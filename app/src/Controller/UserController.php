<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class UserController extends AbstractController
{
    public function __construct(
        private readonly UserRepository $userRepository
    )
    {
    }

    #[Route('/profile', name: 'app_profile', methods: ['GET', 'POST'])]
    public function profile(): Response
    {
        $user = $this->getUser();
        $profil = $this->userRepository->getAllInformation('6946f00d-0416-4fe0-9416-63df410a1118');

        return $this->render('user/profil.html.twig', [
            'profile' => $profil[0],
        ]);
    }
}
