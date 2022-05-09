<?php

namespace App\Entity;

use App\Repository\CommentaireRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CommentaireRepository::class)
 */
class Commentaire
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Comnt;

    /**
     * @ORM\ManyToOne(targetEntity=Place::class, inversedBy="commentaires")
     */
    private $Place;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="commentaires")
     */
    private $User;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getComnt(): ?string
    {
        return $this->Comnt;
    }

    public function setComnt(string $Comnt): self
    {
        $this->Comnt = $Comnt;

        return $this;
    }

    public function getPlace(): ?Place
    {
        return $this->Place;
    }

    public function setPlace(?Place $Place): self
    {
        $this->Place = $Place;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->User;
    }

    public function setUser(?User $User): self
    {
        $this->User = $User;

        return $this;
    }

    public function __toString()
    {
        return "test";
    }


}
