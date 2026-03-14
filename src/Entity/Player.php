<?php

namespace App\Entity;

use App\Repository\PlayerRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: PlayerRepository::class)]
class Player
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['player:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\Length(min: 2, max: 255)]
    #[Groups(['player:read'])]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\Length(min: 2, max: 255)]
    #[Groups(['player:read'])]
    private ?string $surname = null;

    #[ORM\Column]
    #[Assert\NotBlank]
    #[Assert\PositiveOrZero]
    #[Groups(['player:read'])]
    private ?int $number = null;

    #[ORM\Column(length: 10)]
    #[Assert\NotBlank]
    // las posiciones son en español son asi en este orden
    // POR, DF, CAI, CAD, MC, MCD, MCO, MD, MI, EI, DC, ED
    #[Assert\Choice(['GK', 'DF', 'LWB', 'RWB', 'CM', 'CDM', 'CAM', 'RM', 'LM', 'ST', 'RW', 'LW'])]
    #[Groups(['player:read'])]
    private ?string $position = null;

    #[ORM\Column]
    #[Assert\NotBlank]
    #[Assert\Range(min: 0, max: 99)]
    #[Groups(['player:read'])]
    private ?int $overall_rating = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Assert\NotBlank]
    #[Assert\Type(\DateTimeInterface::class)]
    #[Groups(['player:read'])]
    private ?\DateTimeInterface $joined_at = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    #[Groups(['player:read'])]
    private ?\DateTimeInterface $left_at = null;

    #[ORM\Column]
    #[Assert\Type('bool')]
    #[Groups(['player:read'])]
    private ?bool $is_legend = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\Url]
    #[Groups(['player:read'])]
    private ?string $image_url = null;

    #[ORM\Column]
    #[Assert\PositiveOrZero]
    #[Groups(['player:read'])]
    private ?int $matches_played = 0;

    #[ORM\Column]
    #[Assert\PositiveOrZero]
    #[Groups(['player:read'])]
    private ?int $goals = 0;

    #[ORM\Column]
    #[Assert\PositiveOrZero]
    #[Groups(['player:read'])]
    private ?int $assists = 0;

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

    public function getNumber(): ?int
    {
        return $this->number;
    }

    public function setNumber(int $number): static
    {
        $this->number = $number;

        return $this;
    }

    public function getPosition(): ?string
    {
        return $this->position;
    }

    public function setPosition(string $position): static
    {
        $this->position = $position;

        return $this;
    }

    public function getOverallRating(): ?int
    {
        return $this->overall_rating;
    }

    public function setOverallRating(int $overall_rating): static
    {
        $this->overall_rating = $overall_rating;

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

    public function isIsLegend(): ?bool
    {
        return $this->is_legend;
    }

    public function setIsLegend(bool $is_legend): static
    {
        $this->is_legend = $is_legend;

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

    public function getMatchesPlayed(): ?int
    {
        return $this->matches_played;
    }

    public function setMatchesPlayed(int $matches_played): static
    {
        $this->matches_played = $matches_played;

        return $this;
    }

    public function getGoals(): ?int
    {
        return $this->goals;
    }

    public function setGoals(int $goals): static
    {
        $this->goals = $goals;

        return $this;
    }

    public function getAssists(): ?int
    {
        return $this->assists;
    }

    public function setAssists(int $assists): static
    {
        $this->assists = $assists;

        return $this;
    }
}
