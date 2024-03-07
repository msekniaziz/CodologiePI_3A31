<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;


use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
class Category
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]

    #[Assert\NotBlank(message: "Le nom ne peut pas être vide.")]
    #[Assert\Length(min: 5, minMessage: "La description doit contenir au moins 5 caractères.")]
    private ?string $name = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: "Le nom ne peut pas être vide.")]
    #[Assert\Regex(
        pattern: "/^1\d{2}$/",
        message: "Le champ keyCat doit commencer par '1' suivi de deux chiffres."
    )]
    private ?int $keyCat = null;

    #[ORM\Column]
    private ?int $nbrAnnonce = null;

    #[ORM\OneToMany(mappedBy: 'idCat', targetEntity: Annonces::class)]
    private Collection $ancat;

    public function __construct()
    {
        $this->ancat = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getKeyCat(): ?int
    {
        return $this->keyCat;
    }

    public function setKeyCat(int $keyCat): static
    {
        $this->keyCat = $keyCat;

        return $this;
    }

    public function getNbrAnnonce(): ?int
    {
        return $this->nbrAnnonce;
    }

    public function setNbrAnnonce(int $nbrAnnonce): static
    {
        $this->nbrAnnonce = $nbrAnnonce;

        return $this;
    }

    /**
     * @return Collection<int, Annonces>
     */
    public function getAncat(): Collection
    {
        return $this->ancat;
    }

    public function addAncat(Annonces $ancat): static
    {
        if (!$this->ancat->contains($ancat)) {
            $this->ancat->add($ancat);
            $ancat->setIdCat($this);
        }

        return $this;
    }

    public function removeAncat(Annonces $ancat): static
    {
        if ($this->ancat->removeElement($ancat)) {
            // set the owning side to null (unless already changed)
            if ($ancat->getIdCat() === $this) {
                $ancat->setIdCat(null);
            }
        }

        return $this;
    }
}