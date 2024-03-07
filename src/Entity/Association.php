<?php

namespace App\Entity;

use App\Repository\AssociationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: AssociationRepository::class)]
#[UniqueEntity(fields: "nomAssociation", message: "This name is already used")]

class Association
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"Add an association name")]
     private ?string $nomAssociation = null;
   

    #[ORM\Column(length: 500)]
    #[Assert\NotBlank(message:"Add its address")]
    private ?string $adresseAssociation = null;

    #[ORM\Column]
    #[Assert\NotBlank(message:"RIB please")]
    
     #[Assert\Length(
    min:8,
    // max: 21,
    exactMessage: "The RIB should contain at most 20 digits"
     )]
     private ?int $RIB = null;
    //  #[Assert\Regex(
    //      pattern: "/^\d{20}$/",
    //     message: "Invalid RIB format it should contain 20 digits"
    //  )]
   

    #[ORM\Column(length: 255)]
    // #[Assert\NotBlank(message:"Association logo please")]
    private ?string $logoAssociation = null;
    #[ORM\Column(length: 500)]
    #[Assert\NotBlank(message:"Description please")]
    private ?string $descriptionAsso = null;

    #[ORM\OneToMany(mappedBy: 'id_association', targetEntity: DonBienMateriel::class,cascade:['remove'])]
    private Collection $donBienMateriels;

    #[ORM\OneToMany(mappedBy: 'id_association', targetEntity: DonArgent::class)]
    private Collection $donArgents;

   
    public function __toString():string
    {
     return $this->getNomAssociation();
    }

    public function __construct()
    {
        $this->donBienMateriels = new ArrayCollection();
        $this->donArgents = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomAssociation(): ?string
    {
        return $this->nomAssociation;
    }

    public function setNomAssociation(string $nomAssociation): static
    {
        $this->nomAssociation = $nomAssociation;

        return $this;
    }

    public function getAdresseAssociation(): ?string
    {
        return $this->adresseAssociation;
    }

    public function setAdresseAssociation(string $adresseAssociation): static
    {
        $this->adresseAssociation = $adresseAssociation;

        return $this;
    }

    public function getRIB(): ?int
    {
        return $this->RIB;
    }

    public function setRIB(int $RIB): static
    {
        $this->RIB = $RIB;

        return $this;
    }

    public function getLogoAssociation(): ?string
    {
        return $this->logoAssociation;
    }

    public function setLogoAssociation(string $logoAssociation): static
    {
        $this->logoAssociation = $logoAssociation;

        return $this;
    }

    /**
     * @return Collection<int, DonBienMateriel>
     */
    public function getDonBienMateriels(): Collection
    {
        return $this->donBienMateriels;
    }

    public function addDonBienMateriel(DonBienMateriel $donBienMateriel): static
    {
        if (!$this->donBienMateriels->contains($donBienMateriel)) {
            $this->donBienMateriels->add($donBienMateriel);
            $donBienMateriel->setIdAssociation($this);
        }

        return $this;
    }

    public function removeDonBienMateriel(DonBienMateriel $donBienMateriel): static
    {
        if ($this->donBienMateriels->removeElement($donBienMateriel)) {
            // set the owning side to null (unless already changed)
            if ($donBienMateriel->getIdAssociation() === $this) {
                $donBienMateriel->setIdAssociation(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, DonArgent>
     */
    public function getDonArgents(): Collection
    {
        return $this->donArgents;
    }

    public function addDonArgent(DonArgent $donArgent): static
    {
        if (!$this->donArgents->contains($donArgent)) {
            $this->donArgents->add($donArgent);
            $donArgent->setIdAssociation($this);
        }

        return $this;
    }

    public function removeDonArgent(DonArgent $donArgent): static
    {
        if ($this->donArgents->removeElement($donArgent)) {
            // set the owning side to null (unless already changed)
            if ($donArgent->getIdAssociation() === $this) {
                $donArgent->setIdAssociation(null);
            }
        }

        return $this;
    }

    public function getDescriptionAsso(): ?string
    {
        return $this->descriptionAsso;
    }

    public function setDescriptionAsso(string $descriptionAsso): static
    {
        $this->descriptionAsso = $descriptionAsso;

        return $this;
    }
}
