<?php

namespace App\EventListener;

use App\Entity\Utilisateurs;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;

class AuthenticationSuccessListener
{
    private JWTTokenManagerInterface $jwtManager;

    public function __construct(JWTTokenManagerInterface $jwtManager)
    {
        $this->jwtManager = $jwtManager;
    }

    public function onAuthenticationSuccessResponse(AuthenticationSuccessEvent $event): void
    {
        $data = $event->getData();
        $user = $event->getUser();

        // Insertion des données de l'utilisateur
        $data['id'] = $user->getId();
        $data['login'] = $user->getLogin();
        $data['email'] = $user->getEmail();
        $data['is_premium'] = in_array('ROLE_PREMIUM', $user->getRoles());

        // Récupération des données contenues dans le JWT
        $jwt = $this->jwtManager->parse($data['token']);
        $data['token_exp'] = $jwt['exp'];

        $event->setData($data);
    }
}