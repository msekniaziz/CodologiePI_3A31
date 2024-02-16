<?php

namespace App\Entity;

use App\Repository\CommandesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommandesRepository::class)]
class Commandes
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column]
    private ?bool $etat = null;

    #[ORM\OneToMany(mappedBy: 'idAnnonce', targetEntity: Annonces::class)]
    private Collection $idAnnonces;

    #[ORM\ManyToOne(inversedBy: 'idCommande')]
    private ?User $idUserC = null;

    public function __construct()
    {
        $this->idAnnonces = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function isEtat(): ?bool
    {
        return $this->etat;
    }

    public function setEtat(bool $etat): static
    {
        $this->etat = $etat;

        return $this;
    }

    /**
     * @return Collection<int, Annonces>
     */
    public function getIdAnnonces(): Collection
    {
        return $this->idAnnonces;
    }

    public function addIdAnnonce(Annonces $idAnnonce): static
    {
        if (!$this->idAnnonces->contains($idAnnonce)) {
            $this->idAnnonces->add($idAnnonce);
            $idAnnonce->setIdClient($this);
        }

        return $this;
    }

    public function removeIdAnnonce(Annonces $idAnnonce): static
    {
        if ($this->idAnnonces->removeElement($idAnnonce)) {
            // set the owning side to null (unless already changed)
            if ($idAnnonce->getIdClient() === $this) {
                $idAnnonce->setIdClient(null);
            }
        }

        return $this;
    }

    public function getIdUserC(): ?User
    {
        return $this->idUserC;
    }

    public function setIdUserC(?User $idUserC): static
    {
        $this->idUserC = $idUserC;

        return $this;
    }
}
