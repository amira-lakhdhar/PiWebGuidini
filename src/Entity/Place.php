<?php

namespace App\Entity;

use App\Repository\PlaceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=PlaceRepository::class)
 */
class Place
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     */
    private $Type;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     */
    private $Nom;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="adresse should not be empty !!")
     * @Assert\Length(
     *     min=10,
     *     max= 50,
     *     minMessage ="adresse should be >=10",
     *     maxMessage ="adresse should be <=50")
     */
    private $adresse;

    /**
     * @ORM\OneToMany(targetEntity=Avis::class, mappedBy="Place", cascade={"remove"})
     */
    private $avis;

    private $isRated;

    /**
     * @ORM\OneToMany(targetEntity=Pictures::class, mappedBy="place")
     */
    private $pictures;

    /**
     * @ORM\OneToMany(targetEntity=Commentaire::class, mappedBy="Place")
     */
    private $commentaires;

    public function __construct()
    {
        $this->avis = new ArrayCollection();
        $this->pictures = new ArrayCollection();
        $this->commentaires = new ArrayCollection();
    }



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->Type;
    }

    public function setType(string $Type): self
    {
        $this->Type = $Type;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->Nom;
    }

    public function setNom(string $Nom): self
    {
        $this->Nom = $Nom;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }


    /**
     * @return Collection|Avis[]
     */
    public function getAvis(): Collection
    {
        return $this->avis;
    }

    public function addAvi(Avis $avi): self
    {
        if (!$this->avis->contains($avi)) {
            $this->avis[] = $avi;
            $avi->setPlace($this);
        }

        return $this;
    }

    public function removeAvi(Avis $avi): self
    {
        if ($this->avis->removeElement($avi)) {
            // set the owning side to null (unless already changed)
            if ($avi->getPlace() === $this) {
                $avi->setPlace(null);
            }
        }

        return $this;
    }

    /**
     * @return mixed
     */
    public function getIsRated()
    {
        return $this->isRated;
    }

    /**
     * @param mixed $isRated
     */
    public function setIsRated($isRated): void
    {
        $this->isRated = $isRated;
    }

    /**
     * @return Collection|Pictures[]
     */
    public function getPictures(): Collection
    {
        return $this->pictures;
    }

    public function addPicture(Pictures $picture): self
    {
        if (!$this->pictures->contains($picture)) {
            $this->pictures[] = $picture;
            $picture->setPlace($this);
        }

        return $this;
    }

    public function removePicture(Pictures $picture): self
    {
        if ($this->pictures->removeElement($picture)) {
            // set the owning side to null (unless already changed)
            if ($picture->getPlace() === $this) {
                $picture->setPlace(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Commentaire[]
     */
    public function getCommentaires(): Collection
    {
        return $this->commentaires;
    }

    public function addCommentaire(Commentaire $commentaire): self
    {
        if (!$this->commentaires->contains($commentaire)) {
            $this->commentaires[] = $commentaire;
            $commentaire->setPlace($this);
        }

        return $this;
    }

    public function removeCommentaire(Commentaire $commentaire): self
    {
        if ($this->commentaires->removeElement($commentaire)) {
            // set the owning side to null (unless already changed)
            if ($commentaire->getPlace() === $this) {
                $commentaire->setPlace(null);
            }
        }

        return $this;
    }



}
