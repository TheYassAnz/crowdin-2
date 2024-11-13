<?php

namespace App\Entity;

use App\Repository\SourcesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SourcesRepository::class)]
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

    #[ORM\Column]
    private ?int $project_id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $create_date = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $update_date = null;

    #[ORM\ManyToOne(inversedBy: 'sources')]
    private ?Projects $project = null;

    /**
     * @var Collection<int, Translations>
     */
    #[ORM\OneToMany(targetEntity: Translations::class, mappedBy: 'source')]
    private Collection $translations;

    /**
     * @var Collection<int, Translations>
     */
    #[ORM\OneToMany(targetEntity: Translations::class, mappedBy: 'source')]
    private Collection $test;

    #[ORM\ManyToMany(targetEntity: Language::class)]
    #[ORM\JoinTable(name: 'source_language')]
    private Collection $languages;

    public function __construct()
    {
        $this->translations = new ArrayCollection();
        $this->test = new ArrayCollection();
        $this->languages = new ArrayCollection();
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

    public function getProjectId(): ?int
    {
        return $this->project_id;
    }

    public function setProjectId(int $project_id): static
    {
        $this->project_id = $project_id;

        return $this;
    }

    public function getCreateDate(): ?\DateTimeInterface
    {
        return $this->create_date;
    }

    public function setCreateDate(\DateTimeInterface $create_date): static
    {
        $this->create_date = $create_date;

        return $this;
    }

    public function getUpdateDate(): ?\DateTimeInterface
    {
        return $this->update_date;
    }

    public function setUpdateDate(\DateTimeInterface $update_date): static
    {
        $this->update_date = $update_date;

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

    public function getLanguages(): Collection
    {
        return $this->languages;
    }

    public function addLanguage(Language $language): static
    {
        if (!$this->languages->contains($language)) {
            $this->languages[] = $language;
        }

        return $this;
    }

    public function removeLanguage(Language $language): static
    {
        $this->languages->removeElement($language);

        return $this;
    }

    public function getTranslations(): Collection
    {
        return $this->translations;
    }

    public function addTranslation(Translations $translation): static
    {
        if (!$this->translations->contains($translation)) {
            $this->translations->add($translation);
            $translation->setSource($this);
        }

        return $this;
    }

    public function removeTranslation(Translations $translation): static
    {
        if ($this->translations->removeElement($translation)) {
            if ($translation->getSource() === $this) {
                $translation->setSource(null);
            }
        }

        return $this;
    }

    public function getTest(): Collection
    {
        return $this->test;
    }

    public function addTest(Translations $test): static
    {
        if (!$this->test->contains($test)) {
            $this->test->add($test);
            $test->setSource($this);
        }

        return $this;
    }

    public function removeTest(Translations $test): static
    {
        if ($this->test->removeElement($test)) {
            if ($test->getSource() === $this) {
                $test->setSource(null);
            }
        }

        return $this;
    }
}