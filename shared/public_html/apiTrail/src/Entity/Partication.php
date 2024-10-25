<?php

namespace App\Entity;

use App\Repository\ParticationRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ParticationRepository::class)]
class Partication
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
    private ?Event $event_id = null;

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

    public function getEventId(): ?Event
    {
        return $this->event_id;
    }

    public function setEventId(?Event $event_id): static
    {
        $this->event_id = $event_id;

        return $this;
    }
}
