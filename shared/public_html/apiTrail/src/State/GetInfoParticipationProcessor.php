<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\Utilisateurs;
use App\Repository\ParticipationsRepository;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

class GetInfoParticipationProcessor implements ProcessorInterface
{
    public function __construct(
        #[Autowire(service: 'api_platform.doctrine.orm.state.persist_processor')]
        private readonly ProcessorInterface          $persistProcessor,
        private readonly Security $security,
        private readonly ParticipationsRepository   $participationsRepository
    )
    {

    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): mixed
    {
        $user = $this->security->getUser();
        if ($user instanceof Utilisateurs) {
            if($this->participationsRepository->checkIfParticipationExists($user->getId(), $data->getId())){
                $data["participating"] = true;
            } else {
                $data["participating"] = false;
            }
        }

        return $this->persistProcessor->process($data, $operation, $uriVariables, $context);
    }
}
