<?php

namespace App\Entity;

use App\Repository\CoachRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CoachRepository::class)]
class Coach
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['coach:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\Length(min: 2, max: 255)]
    #[Groups(['coach:read'])]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\Length(min: 2, max: 255)]
    #[Groups(['coach:read'])]
    private ?string $surname = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\Length(min: 2, max: 255)]
    #[Groups(['coach:read'])]
    private ?string $role = null;

    // Grupos disponibles (en inglés):
    // GK = entrenador de porteros
    // DEF = entrenador de defensas
    // MID = entrenador de mediocentros
    // FWD = entrenador de delanteros
    // HEAD = entrenador principal / primer equipo
    #[ORM\Column(length: 10)]
    #[Assert\NotBlank]
    #[Assert\Choice(['GK', 'DEF', 'MID', 'FWD', 'HEAD'])]
    #[Groups(['coach:read'])]
    private ?string $group = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\Url]
    #[Groups(['coach:read'])]
    private ?string $image_url = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Assert\NotBlank]
    #[Assert\Type(\DateTimeInterface::class)]
    #[Groups(['coach:read'])]
    private ?\DateTimeInterface $joined_at = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    #[Groups(['coach:read'])]
    private ?\DateTimeInterface $left_at = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['coach:read'])]
    private ?string $bio = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getSurname(): ?string
    {
        return $this->surname;
    }

    public function setSurname(string $surname): static
    {
        $this->surname = $surname;

        return $this;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(string $role): static
    {
        $this->role = $role;

        return $this;
    }

    public function getGroup(): ?string
    {
        return $this->group;
    }

    public function setGroup(string $group): static
    {
        $this->group = $group;

        return $this;
    }

    public function getImageUrl(): ?string
    {
        return $this->image_url;
    }

    public function setImageUrl(string $image_url): static
    {
        $this->image_url = $image_url;

        return $this;
    }

    public function getJoinedAt(): ?\DateTimeInterface
    {
        return $this->joined_at;
    }

    public function setJoinedAt(\DateTimeInterface $joined_at): static
    {
        $this->joined_at = $joined_at;

        return $this;
    }

    public function getLeftAt(): ?\DateTimeInterface
    {
        return $this->left_at;
    }

    public function setLeftAt(?\DateTimeInterface $left_at): static
    {
        $this->left_at = $left_at;

        return $this;
    }

    public function getBio(): ?string
    {
        return $this->bio;
    }

    public function setBio(?string $bio): static
    {
        $this->bio = $bio;

        return $this;
    }
}
