<?php


namespace App\EventListener;

use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;

class AuthenticationSuccessListener
{
    public function onAuthenticationSuccessResponse(AuthenticationSuccessEvent $event): void
    {
        $data = $event->getData();
        $user = $event->getUser();

        $data['data'] = [
            'roles' => $user->getRoles(),
            'login' => $user->getLogin(),
        ];

        $event->setData($data);
    }
}