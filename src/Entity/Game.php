<?php

namespace App\Entity;

use App\Repository\GameRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: GameRepository::class)]
class Game
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['game:read'])]
    private ?int $id = null;

    #[Assert\NotBlank]
    #[Assert\Length(min: 2, max: 255)]
    #[Groups(['game:read'])]
    private ?string $opponent_name = null;

    #[Assert\NotBlank]
    #[Assert\Url]
    #[Groups(['game:read'])]
    private ?string $opponent_logo_url = null;

    #[Assert\Type('bool')]
    #[Groups(['game:read'])]
    private ?bool $is_home = null;

    #[Assert\NotBlank]
    #[Assert\Type(\DateTimeInterface::class)]
    #[Groups(['game:read'])]
    private ?\DateTimeInterface $scheduled_at = null;

    #[Assert\Length(max: 10)]
    #[Groups(['game:read'])]
    private ?string $result = null;

    #[Assert\NotBlank]
    #[Assert\Length(min: 2, max: 255)]
    #[Groups(['game:read'])]
    private ?string $competition = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOpponentName(): ?string
    {
        return $this->opponent_name;
    }

    public function setOpponentName(string $opponent_name): static
    {
        $this->opponent_name = $opponent_name;

        return $this;
    }

    public function getOpponentLogoUrl(): ?string
    {
        return $this->opponent_logo_url;
    }

    public function setOpponentLogoUrl(string $opponent_logo_url): static
    {
        $this->opponent_logo_url = $opponent_logo_url;

        return $this;
    }

    public function isIsHome(): ?bool
    {
        return $this->is_home;
    }

    public function setIsHome(bool $is_home): static
    {
        $this->is_home = $is_home;

        return $this;
    }

    public function getScheduledAt(): ?\DateTimeInterface
    {
        return $this->scheduled_at;
    }

    public function setScheduledAt(\DateTimeInterface $scheduled_at): static
    {
        $this->scheduled_at = $scheduled_at;

        return $this;
    }

    public function getResult(): ?string
    {
        return $this->result;
    }

    public function setResult(?string $result): static
    {
        $this->result = $result;

        return $this;
    }

    public function getCompetition(): ?string
    {
        return $this->competition;
    }

    public function setCompetition(string $competition): static
    {
        $this->competition = $competition;

        return $this;
    }
}
