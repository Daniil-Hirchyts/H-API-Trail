<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Repository\ParticipationsRepository;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

class DeleteParticipationProcessor implements ProcessorInterface
{
    public function __construct(
        private readonly Security $security,
        #[Autowire(service: 'api_platform.doctrine.orm.state.persist_processor')]
        private readonly ProcessorInterface $persistProcessor,
        private readonly ParticipationsRepository $participationsRepository
    )
    {
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): mixed
    {
        //verifi si l'utilisateur est l'owner de la participation si lutilisateur n'est pas admin
        $userIdProvided = $uriVariables['idUser'];
        $participationId = $uriVariables['idParticipation'];
        $actualUser = $this->security->getUser();

        if (!$this->security->isGranted('ROLE_ADMIN') && $actualUser->getId() !== $userIdProvided) {
            throw new \Exception('User not found or not authorized.');
        }

        $userIdOfParticipation = $this->participationsRepository->getParrticipationById($participationId)->getUser()->getId();

        if (!$this->security->isGranted('ROLE_ADMIN') && $actualUser->getId() !== $userIdOfParticipation) {
            throw new \Exception('User not authorized.');
        }

        $this->participationsRepository->deleteParticipation($participationId);

        return $this->persistProcessor->process($data, $operation, $uriVariables, $context);
    }
}
