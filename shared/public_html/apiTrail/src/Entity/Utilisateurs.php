<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Repository\UtilisateursRepository;
use App\State\UtilisateursProcessor;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[UniqueEntity('login', message: 'Ce login est déjà utilisé!')]
#[UniqueEntity('email', message: 'Cette adresse email est déjà utilisée!')]
#[ORM\Entity(repositoryClass: UtilisateursRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_LOGIN', fields: ['login'])]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
#[ApiResource(
    operations: [
        new GetCollection(
            security: 'is_granted("ROLE_USER")',
        ),
        new Get(
            security: 'is_granted("ROLE_USER")',
        ),
        new Post(
            uriTemplate: '/auth/register',
            denormalizationContext: ['groups' => ['utilisateur:create']],
            validationContext: ['groups' => ['Default', 'utilisateur:create']],
            processor: UtilisateursProcessor::class
        ),
        new Patch(
            denormalizationContext: ['groups' => ['utilisateur:update']],
            security: 'is_granted("ROLE_USER")',
            validationContext: ['groups' => ['Default', 'utilisateur:update']],
        ),
        new Delete()
    ]
)]
class Utilisateurs implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    #[Assert\NotBlank(groups: ['utilisateur:create'])]
    #[Assert\Length(min: 4, max: 20, minMessage: 'Il faut au moins 4 caractères pour le login!', maxMessage: 'Il faut moins de 20 caractères pour le login!')]
    #[Groups(['utilisateur:create'])]
    private ?string $login = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['utilisateur:update'])]
    private ?string $nom = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['utilisateur:update'])]
    private ?string $prenom = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['utilisateur:update'])]
    private ?string $telephone = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(groups: ['utilisateur:create'])]
    #[Assert\NotNull(groups: ['utilisateur:create'])]
    #[Assert\Email(message: 'L\'adresse email n\'est pas valide')]
    #[Groups(['utilisateur:create', 'utilisateur:update'])]
    private ?string $email = null;

    #[ORM\Column]
    #[ApiProperty(readable: false, writable: false)]
    private ?string $hash = null;

    #[ORM\Column]
    #[Groups(['utilisateur:create', 'utilisateur:update'])]
    private array $roles = [];

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $created_at = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $updated_at = null;

    #[ApiProperty(readable: false)]
    #[Assert\NotBlank(message: "Le mot de passe ne peut pas être vide.", groups: ['utilisateur:create'])]
    #[Assert\NotNull(groups: ['utilisateur:create'])]
    #[Assert\Length(
        min: 8,
        max: 30,
        minMessage: "Il faut au moins 8 caractères pour le mdp!",
        maxMessage: "Il faut moins de 30 caractères pour le mdp!",
        groups: ['utilisateur:create']
    )]
    #[Assert\Regex(
        pattern: '#^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d\w\W]{8,30}$#',
        message: 'Le mot de passe doit contenir au moins une lettre majuscule, une lettre minuscule, un chiffre et un caractère spécial',
        groups: ['utilisateur:create']
    )]
    #[Groups(['utilisateur:create', 'utilisateur:update'])]
    private ?string $plainPassword = null;

    /**
     * @var Collection<int, Events>
     */
    #[ORM\OneToMany(targetEntity: Events::class, mappedBy: 'organized_by', orphanRemoval: true)]
    private Collection $events;

    /**
     * @var Collection<int, Partications>
     */
    #[ORM\OneToMany(targetEntity: Partications::class, mappedBy: 'user_id', orphanRemoval: true)]
    private Collection $inscrits;

    public function __construct()
    {
        $this->events = new ArrayCollection();
        $this->inscrits = new ArrayCollection();
    }

    public function getLogin(): ?string
    {
        return $this->login;
    }

    public function setLogin(?string $login): void
    {
        $this->login = $login;
    }

    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(?string $plainPassword): void
    {
        $this->plainPassword = $plainPassword;
    }

    public function eraseCredentials(): void
    {
        $this->plainPassword = null;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): static
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(string $telephone): static
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getHash(): ?string
    {
        return $this->hash;
    }

    public function setHash(string $hash): static
    {
        $this->hash = $hash;

        return $this;
    }

    public function removeRole($role): void
    {
        $index = array_search($role, $this->roles);
        if ($index !== false) {
            unset($this->roles[$index]);
        }
    }

    public function addRole($role): void
    {
        if (!in_array($role, $this->roles)) {
            $this->roles[] = $role;
        }
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

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

    public function getPassword(): ?string
    {
        return $this->hash;
    }

    public function getUserIdentifier(): string
    {
        return $this->login;
    }

    /**
     * @return Collection<int, Events>
     */
    public function getEvents(): Collection
    {
        return $this->events;
    }

    public function addEvent(Events $event): static
    {
        if (!$this->events->contains($event)) {
            $this->events->add($event);
            $event->setOrganizedBy($this);
        }

        return $this;
    }

    public function removeEvent(Events $event): static
    {
        if ($this->events->removeElement($event)) {
            // set the owning side to null (unless already changed)
            if ($event->getOrganizedBy() === $this) {
                $event->setOrganizedBy(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Partications>
     */
    public function getInscrits(): Collection
    {
        return $this->inscrits;
    }

    public function addInscription(Partications $partication): static
    {
        if (!$this->inscrits->contains($partication)) {
            $this->inscrits->add($partication);
            $partication->setUserId($this);
        }

        return $this;
    }

    public function removeInscription(Partications $partication): static
    {
        if ($this->inscrits->removeElement($partication)) {
            // set the owning side to null (unless already changed)
            if ($partication->getUserId() === $this) {
                $partication->setUserId(null);
            }
        }

        return $this;
    }
}