<?php

namespace App\Entity;

use App\Repository\AnnoncesRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: AnnoncesRepository::class)]
class Annonces
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "The title cannot be empty.")]
    private ?string $title = null;

    #[ORM\Column(length: 255)]
    private ?string $category = "inactive";

    #[ORM\Column(length: 500)]
    #[Assert\NotBlank(message: "The description cannot be empty.")]
    #[Assert\Length(min: 5, minMessage: "La description doit contenir au moins 5 caractères.")]
    private ?string $description = null;

    #[ORM\Column]

    private ?bool $negociable = null;

    #[ORM\Column(length: 500)]
    #[Assert\NotBlank(message: "The price cannot be empty.")]
    private ?float $prix = null;

    #[ORM\Column(nullable: true)]
    private ?int $status = null;

    #[ORM\Column(length: 255)]
    private ?string $image = null;

    #[ORM\ManyToOne(inversedBy: 'annonces')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'idAnnonces')]
    private ?Commandes $idClient = null;

    #[ORM\ManyToOne(inversedBy: 'ancat')]
    #[ORM\JoinColumn(name: 'id_cat_id', referencedColumnName: 'id')]
    #[Assert\NotBlank(message: "The category cannot be empty.")]
    private ?Category $idCat ;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getCategory(): ?string
    {
        return $this->category;
    }

    public function setCategory(string $category): static
    {
        $this->category = $category;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function isNegociable(): ?bool
    {
        return $this->negociable;
    }

    public function setNegociable(bool $negociable): static
    {
        $this->negociable = $negociable;

        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): static
    {
        $this->prix = $prix;

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(?int $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): static
    {
        $this->image = $image;

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

    public function getIdClient(): ?Commandes
    {
        return $this->idClient;
    }

    public function setIdClient(?Commandes $idClient): static
    {
        $this->idClient = $idClient;

        return $this;
    }


    public function getIdCat(): ?Category
    {
        return $this->idCat;
    }

    public function setIdCat(?Category $idCat): static
    {
        $this->idCat = $idCat;

        return $this;
    }

    public function getStatusText(): string
    {
        return $this->status === 0 ? 'Active' : 'Non active';
    }


}