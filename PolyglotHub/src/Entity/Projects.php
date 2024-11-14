<?php

namespace App\Entity;

use App\Repository\ProjectsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProjectsRepository::class)]
class Projects
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\ManyToOne(targetEntity: Language::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?Language $start_language = null;

    #[ORM\ManyToMany(targetEntity: Language::class)]
    #[ORM\JoinTable(name: 'project_target_languages')]
    private Collection $target_languages;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $create_date = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $update_date = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\OneToMany(targetEntity: Sources::class, mappedBy: 'project')]
    private Collection $sources;

    public function __construct()
    {
        $this->target_languages = new ArrayCollection();
        $this->sources = new ArrayCollection();
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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getStartLanguage(): ?Language
    {
        return $this->start_language;
    }

    public function setStartLanguage(Language $start_language): static
    {
        $this->start_language = $start_language;

        return $this;
    }

    public function getTargetLanguages(): Collection
    {
        return $this->target_languages;
    }

    public function addTargetLanguage(Language $targetLanguage): static
    {
        if (!$this->target_languages->contains($targetLanguage)) {
            $this->target_languages[] = $targetLanguage;
        }

        return $this;
    }

    public function removeTargetLanguage(Language $targetLanguage): static
    {
        $this->target_languages->removeElement($targetLanguage);

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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getSources(): Collection
    {
        return $this->sources;
    }

    public function addSource(Sources $source): static
    {
        if (!$this->sources->contains($source)) {
            $this->sources[] = $source;
            $source->setProject($this);
        }

        return $this;
    }

    public function removeSource(Sources $source): static
    {
        if ($this->sources->removeElement($source)) {
            if ($source->getProject() === $this) {
                $source->setProject(null);
            }
        }

        return $this;
    }
}