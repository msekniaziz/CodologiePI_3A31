<?php

namespace App\Entity;

use App\Repository\ProduitTrocRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ProduitTrocRepository::class)]
class ProduitTroc
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]

    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]

    private ?string $category = null;

    #[ORM\Column(length: 1000)]
    #[Assert\NotBlank]

    private ?string $description = null;

    #[ORM\Column(nullable: true)]

    private ?int $statut = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]

    private ?string $image = null;
    

    #[ORM\ManyToOne(inversedBy: 'produitTrocs')]

    private ?User $id_user = null;

    #[ORM\Column(length: 255)]
    private ?string $nom_produit_recherche = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

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

    public function getStatut(): ?int
    {
        return $this->statut;
    }

    public function setStatut(?int $statut): static
    {
        $this->statut = $statut;

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

    public function getIdUser(): ?User
    {
        return $this->id_user;
    }

    public function setIdUser(?User $id_user): static
    {
        $this->id_user = $id_user;

        return $this;
    }

    public function getNomProduitRecherche(): ?string
    {
        return $this->nom_produit_recherche;
    }

    public function setNomProduitRecherche(string $nom_produit_recherche): static
    {
        $this->nom_produit_recherche = $nom_produit_recherche;

        return $this;
    }
}
