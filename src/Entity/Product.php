<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom_product = null;

    #[ORM\ManyToOne(inversedBy: 'products')]
    private ?User $user = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomProduct(): ?string
    {
        return $this->nom_product;
    }

    public function setNomProduct(string $nom_product): static
    {
        $this->nom_product = $nom_product;

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
}
