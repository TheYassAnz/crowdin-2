<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\ProfilRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ProfilRepository::class)]
#[ApiResource(
    normalizationContext: ["groups" => ["profil_read"]],
    denormalizationContext: ["groups" => ["profil_write"]]
)]
class Profil
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["profil_read", "user_read"])]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(["profil_read", "profil_write"])]
    private ?string $description = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(["profil_read", "profil_write", "user_read"])]
    private ?User $user = null;

    /**
     * @var Collection<int, Language>
     */
    #[ORM\ManyToMany(targetEntity: Language::class)]
    #[Groups(["profil_read", "profil_write", "language_read"])]
    private Collection $favorite_languages;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(["profil_read", "profil_write"])]
    private ?string $picture = null;

    public function __construct()
    {
        $this->favorite_languages = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;
        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): static
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @return Collection<int, Language>
     */
    public function getFavoriteLanguages(): Collection
    {
        return $this->favorite_languages;
    }

    public function addFavoriteLanguage(Language $favoriteLanguage): static
    {
        if (!$this->favorite_languages->contains($favoriteLanguage)) {
            $this->favorite_languages->add($favoriteLanguage);
        }

        return $this;
    }

    public function removeFavoriteLanguage(Language $favoriteLanguage): static
    {
        $this->favorite_languages->removeElement($favoriteLanguage);

        return $this;
    }

    public function getPicture(): ?string
    {
        return $this->picture;
    }

    public function setPicture(?string $picture): static
    {
        $this->picture = $picture;

        return $this;
    }
}
