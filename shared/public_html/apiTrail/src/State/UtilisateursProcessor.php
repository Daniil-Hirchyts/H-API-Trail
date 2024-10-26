<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\Utilisateurs;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UtilisateursProcessor implements ProcessorInterface
{
    public function __construct(
        #[Autowire(service: 'api_platform.doctrine.orm.state.persist_processor')]
        private readonly ProcessorInterface          $persistProcessor,
        private readonly UserPasswordHasherInterface $passwordHasher
    )
    {
    }

    /**
     * @throws \Exception
     */
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): mixed
    {
        if ($data instanceof Utilisateurs && $data->getPlainPassword()) {
            $hashedPassword = $this->passwordHasher->hashPassword($data, $data->getPlainPassword());
            $data->setHash($hashedPassword);
            $data->eraseCredentials();
        }

        if ($operation->getMethod() === 'POST') {
            $data->setCreatedAt(new \DateTime());
        }

        $data->setUpdatedAt(new \DateTime());

        // Reprendre le traitement normal d'API Platform
        return $this->persistProcessor->process($data, $operation, $uriVariables, $context);
    }
}