<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\ProjectsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ProjectsRepository::class)]

#[ApiResource(
    normalizationContext: ["groups" => ["project_read"]],
    denormalizationContext: ["groups" => ["project_write"]]
)]

class Projects
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups("project_read")]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups("project_read")]
    private ?string $name = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(["project_read", "project_write", "user_read"])]
    private ?User $user = null;

    #[ORM\ManyToOne(targetEntity: Language::class)]
    #[ORM\JoinColumn(name: "start_language", referencedColumnName: "id", nullable: false)]
    #[Groups(["project_read", "project_write", "language_read"])]
    private ?Language $start_language = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: true)]
    #[Groups(["project_read", "project_write", "user_read"])]
    private ?User $collaborator = null;

    /**
     * @var Collection<int, Sources>
     */
    #[ORM\OneToMany(mappedBy: 'project', targetEntity: Sources::class, cascade: ['persist', 'remove'])]
    #[Groups(["project_read", "project_write", "source_read"])]
    private Collection $sources;

    /**
     * @var Collection<int, Language>
     */
    #[ORM\ManyToMany(targetEntity: Language::class)]
    #[Groups(["project_read", "project_write", "language_read"])]
    private Collection $target_languages;

    public function __construct()
    {
        $this->sources = new ArrayCollection();
        $this->target_languages = new ArrayCollection();
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



    /**
     * @return Collection<int, Language>
     */
    public function getTargetLanguages(): Collection
    {
        return $this->target_languages;
    }

    public function addTargetLanguage(Language $targetLanguage): static
    {
        if (!$this->target_languages->contains($targetLanguage)) {
            $this->target_languages->add($targetLanguage);
        }

        return $this;
    }

    public function removeTargetLanguage(Language $targetLanguage): static
    {
        $this->target_languages->removeElement($targetLanguage);

        return $this;
    }
}
