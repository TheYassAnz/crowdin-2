<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\TranslationsRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: TranslationsRepository::class)]
#[ApiResource(
    normalizationContext: ["groups" => ["translation_read"]],
    denormalizationContext: ["groups" => ["translation_write"]]
)]
class Translations
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["translation_read", "source_read"])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(["translation_read", "translation_write", "source_read"])]
    private ?string $translated_content = null;

    #[ORM\ManyToOne(targetEntity: Sources::class, inversedBy: 'translations')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(["translation_read", "translation_write"])]
    private ?Sources $source = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(["translation_read", "translation_write", "language_read"])]
    private ?Language $target_language = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;
        return $this;
    }

    public function getTranslatedContent(): ?string
    {
        return $this->translated_content;
    }

    public function setTranslatedContent(string $translated_content): static
    {
        $this->translated_content = $translated_content;
        return $this;
    }

    public function getSource(): ?Sources
    {
        return $this->source;
    }

    public function setSource(?Sources $source): static
    {
        $this->source = $source;
        return $this;
    }

    public function getTargetLanguage(): ?Language
    {
        return $this->target_language;
    }

    public function setTargetLanguage(?Language $target_language): static
    {
        $this->target_language = $target_language;

        return $this;
    }
}
