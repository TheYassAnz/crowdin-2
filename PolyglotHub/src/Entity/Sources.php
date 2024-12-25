<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\SourcesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SourcesRepository::class)]
#[ApiResource]
class Sources
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $content = null;

    #[ORM\Column(length: 255)]
    private ?string $cle = null;

    #[ORM\ManyToOne(inversedBy: 'sources')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private ?Projects $project = null;

    /**
     * @var Collection<int, Translations>
     */
    #[ORM\OneToMany(mappedBy: 'source', targetEntity: Translations::class, cascade: ['persist', 'remove'])]
    private Collection $translations;

    #[ORM\ManyToMany(targetEntity: Language::class)]
    #[ORM\JoinTable(name: 'source_language')]
    private Collection $languages;

    #[ORM\Column(type: 'datetime', nullable: true, options: ['default' => 'CURRENT_TIMESTAMP'])]
    private ?\DateTimeInterface $createDate = null;

    public function __construct()
    {
        $this->translations = new ArrayCollection();
        $this->languages = new ArrayCollection();
        $this->createDate = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;
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

    public function getCle(): ?string
    {
        return $this->cle;
    }

    public function setCle(string $cle): static
    {
        $this->cle = $cle;
        return $this;
    }

    public function getProject(): ?Projects
    {
        return $this->project;
    }

    public function setProject(?Projects $project): static
    {
        $this->project = $project;
        return $this;
    }

    /**
     * @return Collection<int, Translations>
     */
    public function getTranslations(): Collection
    {
        return $this->translations;
    }

    public function addTranslation(Translations $translation): self
    {
        if (!$this->translations->contains($translation)) {
            $this->translations->add($translation);
            $translation->setSource($this);
        }
        return $this;
    }

    public function removeTranslation(Translations $translation): self
    {
        if ($this->translations->removeElement($translation)) {
            if ($translation->getSource() === $this) {
                $translation->setSource(null);
            }
        }
        return $this;
    }

    public function getCreateDate(): ?\DateTimeInterface
    {
        return $this->createDate;
    }

    public function setCreateDate(\DateTimeInterface $createDate): self
    {
        $this->createDate = $createDate;
        return $this;
    }
}
