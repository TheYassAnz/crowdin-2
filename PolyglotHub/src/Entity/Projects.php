<?php

namespace App\Entity;

use App\Repository\ProjectsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\ManyToOne(targetEntity: Language::class)]
    #[ORM\JoinColumn(name: "start_language", referencedColumnName: "id", nullable: false)]
    private ?Language $start_language = null;

    #[ORM\ManyToOne(targetEntity: Language::class)]
    #[ORM\JoinColumn(name: "target_language", referencedColumnName: "id", nullable: false)]
    private ?Language $target_language = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: true)]
    private ?User $collaborator = null;

    /**
     * @var Collection<int, Sources>
     */
    #[ORM\OneToMany(mappedBy: 'project', targetEntity: Sources::class, cascade: ['persist', 'remove'])]
    private Collection $sources;

    public function __construct()
    {
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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getCollaborator(): ?User
    {
        return $this->collaborator;
    }

    public function setCollaborator(?User $collaborator): static
    {
        $this->collaborator = $collaborator;
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

    public function getStartLanguage(): ?Language
    {
        return $this->start_language;
    }

    public function setStartLanguage(?Language $start_language): static
    {
        $this->start_language = $start_language;

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
