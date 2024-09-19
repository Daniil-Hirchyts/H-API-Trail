<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{

    public function __construct(private readonly UserRepository $userRepository)
    {
    }

    #[Route('/', name: 'app_home', methods: ['GET', 'POST'])]
    public function index(): Response
    {
        $cards = $this->userRepository->getCardUsernames();
        return $this->render('home/index.html.twig', [
            'tittle' => 'Home',
            'cards' => $cards
        ]);
    }

    #[Route("/search", name: "search", methods: "POST")]
    public function search(Request $request): JsonResponse
    {
        // Récupérer le contenu JSON de la requête
        $data = json_decode($request->getContent(), true);
        $query = $data['query'] ?? '';

        // Exécuter la requête de recherche
        $results = $this->userRepository->findBySearchQuery($query);

        // Transformation du résultat en format JSON
        $formattedResults = [];
        foreach ($results as $result) {
            $formattedResults[] = [
                'username' => $result['username'],
            ];
        }

        // Retourner les résultats sous forme de JSON
        return new JsonResponse(['cards' => $formattedResults]);
    }

}
