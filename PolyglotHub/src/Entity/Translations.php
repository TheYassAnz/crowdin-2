<?php

namespace App\Entity;

use App\Repository\TranslationsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TranslationsRepository::class)]
class Translations
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $translated_content = null;

    #[ORM\ManyToOne(targetEntity: Sources::class, inversedBy: 'translations')]
    #[ORM\JoinColumn(name: "source_id", referencedColumnName: "id", nullable: false)]
    private ?Sources $source = null;

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
}