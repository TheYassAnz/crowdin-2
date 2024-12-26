<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\SourcesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: SourcesRepository::class)]
#[ApiResource(
    normalizationContext: ["groups" => ["source_read"]],
    denormalizationContext: ["groups" => ["source_write"]]
)]
class Sources
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["source_read", "project_read"])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(["source_read", "source_write", "project_read"])]
    private ?string $content = null;

    #[ORM\Column(length: 255)]
    #[Groups(["source_read", "source_write", "project_read"])]
    private ?string $cle = null;

    #[ORM\ManyToOne(inversedBy: 'sources')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    #[Groups(["source_read", "source_write"])]
    private ?Projects $project = null;

    /**
     * @var Collection<int, Translations>
     */
    #[ORM\OneToMany(mappedBy: 'source', targetEntity: Translations::class, cascade: ['persist', 'remove'])]
    #[Groups(["source_read", "source_write"])]
    private Collection $translations;

    #[ORM\ManyToMany(targetEntity: Language::class)]
    #[ORM\JoinTable(name: 'source_language')]
    #[Groups(["source_read", "source_write", "language_read"])]
    private Collection $languages;

    #[ORM\Column(type: 'datetime', nullable: true, options: ['default' => 'CURRENT_TIMESTAMP'])]
    #[Groups(["source_read", "source_write"])]
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
