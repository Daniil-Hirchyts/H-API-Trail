<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Link;
use ApiPlatform\Metadata\Post;
use App\Repository\ParticipationsRepository;
use App\State\DeleteParticipationProcessor;
use App\State\IsOwnerOfEventProcessor;
use App\State\ParticipationUtilisateurProcessor;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\UniqueConstraint;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;

#[UniqueEntity(fields: ['user', 'event'], message: "Un user ne peut pas être inscrit plus d'une fois au même event.")]
#[UniqueConstraint(name: 'UNIQ_USER_EVENT', columns: ['user_id', 'event_id'])]
#[ApiResource(
    uriTemplate: '/utilisateurs/{idUser}/participations',
    operations: [
        new GetCollection(
            description: "Récupérer les participations d'un utilisateur",
            security: "is_granted('ROLE_USER')",
        ),
        new Post(
            security: "is_granted('ROLE_USER')",
            processor: ParticipationUtilisateurProcessor::class,
        ),
        new Delete(
            description: "Supprimer une participation",
            security: "(is_granted('ROLE_USER') and object.getOwner() == user) or is_granted('ROLE_ADMIN')",
        )
    ],
    uriVariables: [
        'idUser' => new Link(
            toProperty: 'user',
            fromClass: Utilisateurs::class,
        )
    ],
    normalizationContext: ["groups" => ['participation:read']],
    denormalizationContext: ["groups" => ['participation:write']],
    validationContext: ["groups" => ["Default", 'participation:write']],
)]
#[ApiResource(
    uriTemplate: '/utilisateurs/{idUser}/participations/{idParticipation}',
    operations: [
        new Delete(
            description: "Supprimer une participation",
            security: "is_granted('ROLE_USER') or is_granted('ROLE_ADMIN')",
            processor: DeleteParticipationProcessor::class,
        )
    ],
    uriVariables: [
        'idUser' => new Link(
            toProperty: 'user',
            fromClass: Utilisateurs::class,
        ),
        'idParticipation' => new Link(
            fromClass: Participations::class,
        )
    ],
    normalizationContext: ["groups" => ['participation:event:read']],
)]
#[ApiResource(
    uriTemplate: '/events/{idEvent}/participations',
    operations: [
        new GetCollection(
            description: "Récupérer les participations d'un event",
            security: "is_granted('ROLE_ORGA') or is_granted('ROLE_ADMIN')",
        ),
    ],
    uriVariables: [
        'idEvent' => new Link(
            toProperty: 'event',
            fromClass: Events::class,
        )
    ],
    normalizationContext: ["groups" => ['participation:utilisateur:read']],
    denormalizationContext: ["groups" => ['participation:utilisateur:write']],
    validationContext: ["groups" => ["Default", 'participation:utilisateur:write']]
)]
#[ApiResource(
    operations: [
        new GetCollection(
            description: "Récupère les participations",
            security: "is_granted('ROLE_ADMIN')",
        ),
    ],
    normalizationContext: ["groups" => ['participation:read']],
    denormalizationContext: ["groups" => ['participation:write']],
    validationContext: ["groups" => ['Default', 'participation:write']]
)]
#[Entity(repositoryClass: ParticipationsRepository::class)]
class Participations
{
    #[Id]
    #[GeneratedValue]
    #[Column]
    #[Groups(['participation:read','participation:utilisateur:read','participation:event:read', 'participation:utlisateur:write'])]
    private ?int $id = null;

    #[ManyToOne(inversedBy: 'participations')]
    #[JoinColumn(name: 'user_id', nullable: false, onDelete: "CASCADE")]
    #[Groups(['participation:write', 'participation:read','participation:utilisateur:read', 'participation:utlisateur:write','participation:event:read'])]
    private ?Utilisateurs $user = null;

    #[ManyToOne(inversedBy: 'participations')]
    #[JoinColumn(name: 'event_id', nullable: false, onDelete: "CASCADE")]
    #[NotBlank(groups: ['participation:write','event:write'])]
    #[NotNull(groups: ['participation:write','event:write'])]
    #[Groups(['participation:write', 'participation:read','participation:event:read', 'participation:utlisateur:write','participation:event:read'])]
    private ?Events $event = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(string $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getUser(): ?Utilisateurs
    {
        return $this->user;
    }

    public function setUser(?Utilisateurs $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getEvent(): ?Events
    {
        return $this->event;
    }

    public function setEvent(?Events $event): static
    {
        $this->event = $event;

        return $this;
    }

    public function getOwner(): ?Utilisateurs
    {
        return $this->user;
    }

    public function getOwnerOfEvent(): ?Utilisateurs
    {
        return $this->event->getOrganizedBy();
    }
}