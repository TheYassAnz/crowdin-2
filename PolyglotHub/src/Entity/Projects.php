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

    #[ORM\Column(length: 255)]
    private ?string $start_language = null;

    #[ORM\Column(length: 255)]
    private ?string $target_language = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $create_date = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $update_date = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\ManyToMany(targetEntity: Language::class)]
    #[ORM\JoinTable(name: 'project_language')]
    private Collection $languages;

    /**
     * @var Collection<int, Sources>
     */
    #[ORM\OneToMany(targetEntity: Sources::class, mappedBy: 'project')]
    private Collection $sources;

    public function __construct()
    {
        $this->languages = new ArrayCollection();
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

    public function getStartLanguage(): ?string
    {
        return $this->start_language;
    }

    public function setStartLanguage(string $start_language): static
    {
        $this->start_language = $start_language;

        return $this;
    }

    public function getTargetLanguage(): ?string
    {
        return $this->target_language;
    }

    public function setTargetLanguage(string $target_language): static
    {
        $this->target_language = $target_language;

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

    /**
     * @return Collection<int, Sources>
     */
    public function getSources(): Collection
    {
        return $this->sources;
    }

    public function addSource(Sources $source): static
    {
        if (!$this->sources->contains($source)) {
            $this->sources->add($source);
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