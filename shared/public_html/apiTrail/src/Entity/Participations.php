<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Link;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Repository\ParticipationsRepository;
use App\State\ParticipationsProcessor;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ApiResource(
    operations: [
        new GetCollection(
            security: "is_granted('ROLE_ADMIN')",
        ),
        new GetCollection(
            uriTemplate: '/events/{idEvent}/participations',
            uriVariables: [
                'idEvent' => new Link(
                    fromProperty: 'participations',
                    fromClass: Events::class
                )
            ],
            security: "(is_granted('ROLE_ORGA') and object.getOwnerOfEvent() == user) or is_granted('ROLE_ADMIN')",
        ),
        new GetCollection(
            uriTemplate: '/utilisateurs/{idUser}/participations',
            uriVariables: [
                'idUser' => new Link(
                    fromProperty: 'participations',
                    fromClass: Utilisateurs::class
                )
            ],
            security: "(is_granted('ROLE_USER') and object.getOwner() == user) or is_granted('ROLE_ADMIN')",
        ),
        new Post(
            denormalizationContext: ['groups' => ['participation:create']],
            security: "is_granted('ROLE_USER')",
            processor: ParticipationsProcessor::class
        ),
        new Delete(
            security: "(is_granted('ROLE_USER') and object.getOwner() == user) or is_granted('ROLE_ADMIN')",
        )
    ]
)]
#[ORM\Entity(repositoryClass: ParticipationsRepository::class)]
class Participations
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'partications')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['participation:create'])]
    private ?Utilisateurs $user_id = null;

    #[ORM\ManyToOne(inversedBy: 'partications')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['participation:create'])]
    private ?Events $event_id = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(string $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getUserId(): ?Utilisateurs
    {
        return $this->user_id;
    }

    public function setUserId(?Utilisateurs $user_id): static
    {
        $this->user_id = $user_id;

        return $this;
    }

    public function getEventId(): ?Events
    {
        return $this->event_id;
    }

    public function setEventId(?Events $event_id): static
    {
        $this->event_id = $event_id;

        return $this;
    }

    public function getOwner(): ?Utilisateurs
    {
        return $this->user_id;
    }

    public function getOwnerOfEvent(): ?Utilisateurs
    {
        return $this->event_id->getOrganizedBy();
    }
}