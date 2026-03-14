<?php

namespace App\Entity;

use App\Repository\NewsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: NewsRepository::class)]
class News
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['news:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\Length(min: 5, max: 255)]
    #[Groups(['news:read'])]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank]
    #[Groups(['news:read'])]
    private ?string $content = null;

    // Categorías disponibles (en inglés):
    // MATCH   = crónica / resultado de partido
    // SIGNING = fichaje o salida de jugador
    // CLUB    = noticias generales del club
    // EVENT   = eventos y actividades
    #[ORM\Column(length: 10)]
    #[Assert\NotBlank]
    #[Assert\Choice(['MATCH', 'SIGNING', 'CLUB', 'EVENT'])]
    #[Groups(['news:read'])]
    private ?string $category = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Url]
    #[Groups(['news:read'])]
    private ?string $image_url = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Assert\NotBlank]
    #[Assert\Type(\DateTimeInterface::class)]
    #[Groups(['news:read'])]
    private ?\DateTimeInterface $published_at = null;

    #[ORM\Column]
    #[Assert\Type('bool')]
    #[Groups(['news:read'])]
    private ?bool $is_featured = false;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): static
    {
        $this->content = $content;

        return $this;
    }

    public function getCategory(): ?string
    {
        return $this->category;
    }

    public function setCategory(string $category): static
    {
        $this->category = $category;

        return $this;
    }

    public function getImageUrl(): ?string
    {
        return $this->image_url;
    }

    public function setImageUrl(?string $image_url): static
    {
        $this->image_url = $image_url;

        return $this;
    }

    public function getPublishedAt(): ?\DateTimeInterface
    {
        return $this->published_at;
    }

    public function setPublishedAt(\DateTimeInterface $published_at): static
    {
        $this->published_at = $published_at;

        return $this;
    }

    public function isIsFeatured(): ?bool
    {
        return $this->is_featured;
    }

    public function setIsFeatured(bool $is_featured): static
    {
        $this->is_featured = $is_featured;

        return $this;
    }
}
