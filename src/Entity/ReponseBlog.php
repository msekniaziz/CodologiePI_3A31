<?php

namespace App\Entity;
use Doctrine\Common\Collections\Collection;

use App\Repository\ReponseBlogRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: ReponseBlogRepository::class)]
class ReponseBlog
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $contenu = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column]
    private ?int $nb_likes = null;

    #[ORM\ManyToOne(inversedBy: 'reponseBlogs')]
    #[Assert\NotBlank(message:  "You should insert the content of your response")]

    private ?Blog $blog = null;

    #[ORM\ManyToOne(inversedBy: 'reponseBlogs')]
    private ?User $id_user_reponse = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContenu(): ?string
    {
        return $this->contenu;
    }

    public function setContenu(string $contenu): static
    {
        $this->contenu = $contenu;

        return $this;
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

    public function getNbLikes(): ?int
    {
        return $this->nb_likes;
    }

    public function setNbLikes(int $nb_likes): static
    {
        $this->nb_likes = $nb_likes;

        return $this;
    }

    public function getBlog(): ?Blog
    {
        return $this->blog;
    }

    public function setBlog(?Blog $blog): static
    {
        $this->blog = $blog;

        return $this;
    }

    public function getIdUserReponse(): ?User
    {
        return $this->id_user_reponse;
    }

    public function setIdUserReponse(?User $id_user_reponse): static
    {
        $this->id_user_reponse = $id_user_reponse;

        return $this;
    }
}
