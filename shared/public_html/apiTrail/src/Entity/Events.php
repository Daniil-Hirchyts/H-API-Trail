<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Link;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Repository\EventsRepository;
use App\State\EventsProcessor;
use App\Enum\Sport;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

#[ApiResource(
    operations: [
        new GetCollection(
            security: "is_granted('ROLE_USER')",
        ),
        new GetCollection(
            uriTemplate: '/utilisateurs/{idOrga}/events',
            uriVariables: [
                'idOrga' => new Link(
                    fromProperty: 'events',
                    fromClass: Utilisateurs::class
                )
            ],
            security: "is_granted('ROLE_ORGA')",
        ),
        new Post(
            denormalizationContext: ['groups' => ['event:create']],
            security: "is_granted('ROLE_ORGA') or is_granted('ROLE_ADMIN')",
            processor: EventsProcessor::class
        ),
        new Patch(
            denormalizationContext: ['groups' => ['event:update']]
        ),
        new Delete()
    ],
    normalizationContext: ['groups' => ['event:read']]
)]
#[ORM\Entity(repositoryClass: EventsRepository::class)]
class Events
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['event:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['event:read', 'event:create', 'event:update'])]
    #[Assert\Length(min: 4, max: 40, minMessage: 'Il faut au moins 4 caractères!', maxMessage: 'Il faut moins de 40 caractères!')]
    private ?string $name = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups(['event:read', 'event:create', 'event:update'])]
    private ?\DateTimeInterface $start_at = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups(['event:read', 'event:create', 'event:update'])]
    private ?\DateTimeInterface $end_at = null;

    #[ORM\Column(enumType: Sport::class)]
    #[Groups(['event:read', 'event:create', 'event:update'])]
    private ?Sport $sport = null;

    #[ORM\Column(length: 255)]
    #[Groups(['event:read', 'event:create', 'event:update'])]
    #[Assert\Length(min: 4, max: 255, minMessage: 'Il faut au moins 4 caractères!', maxMessage: 'Il faut moins de 255 caractères!')]
    private ?string $localisation = null;

    #[ORM\Column(length: 1000)]
    #[Groups(['event:read', 'event:create', 'event:update'])]
    #[Assert\Length(min: 4, max: 1000, minMessage: 'Il faut au moins 4 caractères!', maxMessage: 'Il faut moins de 1000 caractères!')]
    private ?string $description = null;

    #[ORM\Column]
    #[Groups(['event:read', 'event:create', 'event:update'])]
    #[Assert\GreaterThanOrEqual(1, message: 'Le nombre maximal de participants doit être au moins 1.')]
    private ?int $max_participants = null;

    #[ORM\Column(type: Types::BOOLEAN, options: ['default' => true])]
    #[Groups(['event:read', 'event:create', 'event:update'])]
    private ?bool $participants_visible = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['event:read', 'event:create', 'event:update'])]
    #[Assert\Positive(message: 'Le prix doit être supérieur à 0.')]
    private ?float $price = null;

    #[ORM\ManyToOne(inversedBy: 'events')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['event:read', 'event:create'])]
    #[ApiProperty(readable: true, writable: false)]
    private ?Utilisateurs $organized_by = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups(['event:read'])]
    private ?\DateTimeInterface $created_at = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups(['event:read'])]
    private ?\DateTimeInterface $updated_at = null;

    /**
     * @var Collection<int, Participations>
     */
    #[ORM\OneToMany(targetEntity: Participations::class, mappedBy: 'event_id', orphanRemoval: true)]
    #[Groups(['event:read'])]
    private Collection $participations;

    #[Assert\Callback]
    public function validateDateOrder(ExecutionContextInterface $context): void
    {
        if ($this->start_at && $this->end_at && $this->start_at >= $this->end_at) {
            $context->buildViolation('La date de début doit être avant la date de fin.')
                ->atPath('start_at')
                ->addViolation();
            $context->buildViolation('La date de fin doit être après la date de début.')
                ->atPath('end_at')
                ->addViolation();
        }
    }

    public function __construct()
    {
        $this->participations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(string $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getStartAt(): ?\DateTimeInterface
    {
        return $this->start_at;
    }

    public function setStartAt(\DateTimeInterface $start_at): static
    {
        $this->start_at = $start_at;

        return $this;
    }

    public function getEndAt(): ?\DateTimeInterface
    {
        return $this->end_at;
    }

    public function setEndAt(\DateTimeInterface $end_at): static
    {
        $this->end_at = $end_at;

        return $this;
    }

    public function getSport(): ?Sport
    {
        return $this->sport;
    }

    public function setSport(?Sport $sport): static
    {
        $this->sport = $sport;

        return $this;
    }

    public function getLocalisation(): ?string
    {
        return $this->localisation;
    }

    public function setLocalisation(string $localisation): static
    {
        $this->localisation = $localisation;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getMaxParticipants(): ?int
    {
        return $this->max_participants;
    }

    public function setMaxParticipants(int $max_participants): static
    {
        $this->max_participants = $max_participants;

        return $this;
    }

    public function isParticipantsVisible(): ?bool
    {
        return $this->participants_visible;
    }

    public function setParticipantsVisible(bool $participants_visible): static
    {
        $this->participants_visible = $participants_visible;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(?float $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getOrganizedBy(): ?Utilisateurs
    {
        return $this->organized_by;
    }

    public function setOrganizedBy(?Utilisateurs $organized_by): static
    {
        $this->organized_by = $organized_by;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): static
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(\DateTimeInterface $updated_at): static
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    /**
     * @return Collection<int, Participations>
     */
    public function getParticipations(): Collection
    {
        if ($this->participants_visible) {
            return $this->participations;
        }

        return new ArrayCollection();
    }

    public function addParticipations(Participations $participations): static
    {
        if (!$this->participations->contains($participations)) {
            $this->participations->add($participations);
            $participations->setEventId($this);
        }

        return $this;
    }

    public function removeParticipations(Participations $participations): static
    {
        if ($this->participations->removeElement($participations)) {
            // set the owning side to null (unless already changed)
            if ($participations->getEventId() === $this) {
                $participations->setEventId(null);
            }
        }

        return $this;
    }
}