<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\LanguageRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: LanguageRepository::class)]
#[ApiResource(
    normalizationContext: ["groups" => ["language_read"]],
    denormalizationContext: ["groups" => ["language_write"]]
)]
class Language
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["language_read", "project_read", "source_read"])]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    #[Groups(["language_read", "language_write", "project_read", "source_read"])]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    #[Groups(["language_read", "language_write", "project_read", "source_read"])]
    private ?string $description = null;

    #[ORM\Column(length: 10)]
    #[Groups(["language_read", "language_write", "project_read", "source_read"])]
    private ?string $code = null;

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): static
    {
        $this->code = $code;

        return $this;
    }
}
