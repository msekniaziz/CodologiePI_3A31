<?php

namespace App\Entity;

use App\Repository\ProduitTrocWithRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ProduitTrocWithRepository::class)]
class ProduitTrocWith
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "You should insert the name of your product ")]

    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "You should insert the category of your product")]

    private ?string $category = null;

    #[ORM\Column(length: 1000)]
    #[Assert\NotBlank(message: "You should insert the description of your product")]

    private ?string $description = null;

    #[ORM\Column(length: 255)]

    private ?string $image = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?ProduitTroc $prod_idTroc = null;

   // #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    //private ?User $user_id_Tchange = null;

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

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): static
    {
        $this->image = $image;

        return $this;
    }

    public function getProdIdTroc(): ?ProduitTroc
    {
        return $this->prod_idTroc;
    }

    public function setProdIdTroc(?ProduitTroc $prod_idTroc): static
    {
        $this->prod_idTroc = $prod_idTroc;

        return $this;
    }

   
}
