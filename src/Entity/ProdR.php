<?php

namespace App\Entity;

use App\Repository\ProdRRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use phpDocumentor\Reflection\Types\Integer;
// use Vich\UploaderBundle\Mapping\Annotation as Vich;
// use Symfony\Component\HttpFoundation\File\File;
// use Symfony\Component\DependencyInjection\ContainerAwareInterface;
// use Symfony\Component\DependencyInjection\ContainerInterface;
// use Oneup\UploaderBundle\Uploader\OrphanageUploader;
// use Oneup\UploaderBundle\Uploader\OneUpUploaderInterface;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: ProdRRepository::class)]
// #[Vich\Uploadable]
class ProdR
{


    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne()]
    #[ORM\JoinColumn(nullable: false)]
    // #[ORM\ManyToOne(cascade: ["persist", "remove"])]
    // #[ORM\JoinColumn(nullable: false)]
    private ?User $user_id = null;

    #[ORM\ManyToOne(inversedBy: 'prodRs')]
    #[ORM\JoinColumn(nullable: false)]
    // #[Assert\NotBlank(message: "ptc id please")]

    private ?PtCollect $ptc_id = null;

    // #[ORM\ManyToOne(inversedBy: 'prodR_type', cascade: ["persist", "remove"])]
    // #[ORM\JoinColumn(nullable: false, onDelete: "CASCADE")]

    #[ORM\ManyToOne(inversedBy: 'prodR_type')]
    #[ORM\JoinColumn(nullable: false)]
    // #[Assert\NotBlank(message: "type  please")]

    private ?TypeDispo $typeProd_id = null;

    #[ORM\Column(nullable: true)]
    private ?bool $statut = null;

    #[ORM\Column(length: 255, nullable: true)]
    // #[Assert\Length(min: 10, minMessage: "the description should at least have 10 caracters")]
    private ?string $description = null;



    #[ORM\Column(length: 255, nullable: true)]
    // #[Assert\Length(min: 3, minMessage: "the name should at least have 3 caracters")]
    private ?string $nomP = null;


    #[ORM\Column(length: 255)]
    private ?string $justificatif = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $lastUpdate = null;

    public function __toString()
    {
        return $this->id; // ou toute autre propriété à afficher
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserId(): ?User
    {
        return $this->user_id;
    }

    public function setUserId(?User $user): static
    {
        $this->user_id = $user;

        return $this;
    }

    public function getPtcId(): ?PtCollect
    {
        return $this->ptc_id;
    }

    public function setPtcId(?PtCollect $ptc_id): static
    {
        $this->ptc_id = $ptc_id;

        return $this;
    }

    public function getTypeProdId(): ?TypeDispo
    {
        return $this->typeProd_id;
    }

    public function setTypeProdId(?TypeDispo $typeProd_id): static
    {
        $this->typeProd_id = $typeProd_id;

        return $this;
    }

    public function isStatut(): ?bool
    {
        return $this->statut;
    }

    public function setStatut(?bool $statut): static
    {
        $this->statut = $statut;

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

    public function getNomP(): ?string
    {
        return $this->nomP;
    }

    public function setNomP(?string $nomP): static
    {
        $this->nomP = $nomP;

        return $this;
    }





    public function getJustificatif(): ?string
    {
        return $this->justificatif;
    }

    public function setJustificatif(string $justificatif): static
    {
        $this->justificatif = $justificatif;

        return $this;
    }

    public function getLastUpdate(): ?\DateTimeInterface
    {
        return $this->lastUpdate;
    }

    public function setLastUpdate(?\DateTimeInterface $lastUpdate): static
    {
        $this->lastUpdate = $lastUpdate;

        return $this;
    }
}
