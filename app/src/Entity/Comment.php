<?php

namespace App\Entity;

use App\Repository\CommentRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CommentRepository::class)]
#[ORM\Table(name: '`comment`')]
class Comment
{
    #[ORM\Id]
    #[ORM\Column]
    #[ORM\GeneratedValue]
    private ?int $id = null;

    #[ORM\Column]
    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotNull]
    private ?int $sender = null;

    #[ORM\Column]
    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotNull]
    private ?int $recipient = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotNull]
    private ?string $comment = null;

    #[ORM\Column]
    #[Assert\NotNull]
    private ?int $stars = null;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getSender(): ?int
    {
        return $this->sender;
    }

    public function setSender(int $sender): static
    {
        $this->sender = $sender;

        return $this;
    }

    public function getRecipient(): ?int
    {
        return $this->recipient;
    }

    public function setRecipient(int $recipient): static
    {
        $this->recipient = $recipient;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(string $comment): static
    {
        $this->comment = $comment;

        return $this;
    }

    public function getStars(): ?int
    {
        return $this->stars;
    }

    public function setStars(int $stars): static
    {
        $this->stars = $stars;

        return $this;
    }
}
