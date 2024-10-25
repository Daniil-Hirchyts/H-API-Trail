<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Link;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Repository\ParticationRepository;
use Doctrine\ORM\Mapping as ORM;

#[ApiResource(
    operations: [
        new GetCollection(
            security: "is_granted('ROLE_ADMIN')",
        ),
        new GetCollection(
            uriTemplate: '/events/{idEvent}/partications',
            uriVariables: [
                'idEvent' => new Link(
                    fromProperty: 'partications',
                    fromClass: Events::class
                )
            ],
            security: "is_granted('ROLE_ORGA') or is_granted('ROLE_ADMIN')",
        ),
        new GetCollection(
            uriTemplate: '/utilisateurs/{idUser}/partications',
            uriVariables: [
                'idUser' => new Link(
                    fromProperty: 'partications',
                    fromClass: Utilisateurs::class
                )
            ],
        ),
        new Get(),
        new Post(),
        new Patch(),
        new Delete()
    ]
)]
#[ORM\Entity(repositoryClass: ParticationRepository::class)]
class Partications
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'partications')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Utilisateurs $user_id = null;

    #[ORM\ManyToOne(inversedBy: 'partications')]
    #[ORM\JoinColumn(nullable: false)]
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
}
