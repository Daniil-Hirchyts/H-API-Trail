<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\Utilisateurs;
use App\Repository\EventsRepository;
use App\Repository\ParticipationsRepository;
use App\Repository\UtilisateursRepository;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

class ParticipationUtilisateurProcessor implements ProcessorInterface
{
    public function __construct(
        #[Autowire(service: 'api_platform.doctrine.orm.state.persist_processor')]
        private readonly ProcessorInterface $persistProcessor,
        private readonly ParticipationsRepository $participationsRepository,
        private readonly UtilisateursRepository $utilisateursRepository,
        private readonly Security $security,
        private readonly EventsRepository $eventsRepository
    )
    {
    }

    /**
     * @throws \Exception
     */
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): mixed
    {
        $userId = $data->getUser()->getId();
        $eventId = $data->getEvent()->getId();
        $actualUser = $this->security->getUser();

        if ($userId == null){
            $userId = $actualUser->getId();
        }

        // Récupérer l'utilisateur à partir de l'ID

        if ($actualUser->getId() !== $userId && !$this->security->isGranted('ROLE_ADMIN')) {
            throw new \Exception('User not found or not authorized.');
        }

        // Récupérer l'événement à partir de l'ID
        $event = $this->eventsRepository->find($eventId);
        if (!$event) {
            throw new \Exception('Event not found.');
        }

        if ($event->getMaxParticipants() - $this->participationsRepository->getCountOfParticipationsByEvent($eventId) <= 0) {
            throw new \Exception('No available slots for this event.');
        }

        // Vérifier si l'utilisateur est déjà inscrit à l'événement
        if ($this->participationsRepository->checkIfParticipationExists($userId, $eventId)) {
            throw new \Exception('User is already registered for this event.');
        }

        // Vérifier si les dates de l'événement ne chevauchent pas celles d'un autre événement auquel l'utilisateur participe
        $this->verifyIfAnyDatesOfAParticipationOverlapWithUs($event, $userId, $eventId);

        $data->setUser($actualUser);

        return $this->persistProcessor->process($data, $operation, $uriVariables, $context);
    }

    public function verifyIfAnyDatesOfAParticipationOverlapWithUs($event, $userId, $eventId): void
    {
        $userParticipations = $this->participationsRepository->findBy(['user' => $userId]);
        foreach ($userParticipations as $participation) {
            $participatedEvent = $participation->getEvent();
            if ($participatedEvent->getId() === $eventId) {
                continue; // Skip the event the user is trying to register for
            }
            if (
                ($event->getStartAt() >= $participatedEvent->getStartAt() && $event->getStartAt() <= $participatedEvent->getEndAt()) ||
                ($event->getEndAt() >= $participatedEvent->getEndAt() && $event->getEndAt() <= $participatedEvent->getEndAt())
            ) {
                throw new \Exception('Event dates overlap with another event the user is participating in.');
            }
        }
    }
}