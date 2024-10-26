<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\Participations;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

class ParticipationsProcessor implements ProcessorInterface
{
    public function __construct(
        #[Autowire(service: 'api_platform.doctrine.orm.state.persist_processor')]
        private readonly ProcessorInterface $persistProcessor,
        private readonly Security $security
    ) {
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): mixed
    {
        $user = $this->security->getUser();
        if ($data instanceof Participations) {
            if ($this->security->isGranted('ROLE_USER') && !$this->security->isGranted('ROLE_ADMIN')) {
                if ($data->getUserId() !== null && $data->getUserId() !== $user) {
                    throw new \InvalidArgumentException('You can only post participations for yourself.');
                }
                $data->setUserId($user);
            } elseif ($this->security->isGranted('ROLE_ADMIN')) {
                if ($data->getUserId() === null) {
                    $data->setUserId($user);
                }
            }

            // Ensure event_id is always set
            if ($data->getEventId() === null) {
                throw new \InvalidArgumentException('The event_id must be set.');
            }
        }

        return $this->persistProcessor->process($data, $operation, $uriVariables, $context);
    }
}