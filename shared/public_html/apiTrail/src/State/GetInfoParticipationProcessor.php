<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Repository\ParticipationsRepository;
use Doctrine\DBAL\Exception;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

class GetInfoParticipationProcessor implements ProcessorInterface
{
    public function __construct(
        #[Autowire(service: 'api_platform.doctrine.orm.state.persist_processor')]
        private readonly ProcessorInterface $processor,
        private readonly ParticipationsRepository $participationsRepository,
        private readonly Security $security)
    {
    }

    /**
     * @throws Exception
     */
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): mixed
    {
        dump($data);
        $participationId = $uriVariables['idParticipation'];
        $actualUser = $this->security->getUser();

        $userId = $this->participationsRepository->getParticipationById($participationId)->getUser()->getId();

        if ($actualUser->getId() != $userId && !$this->security->isGranted('ROLE_ADMIN')) {
            throw new Exception('User not found or not authorized.');
        }

        return $this->processor->process($data, $operation, $uriVariables, $context);
    }
}
