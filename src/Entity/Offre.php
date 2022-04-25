<?php

namespace App\Entity;

use App\Repository\OffreRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass=OffreRepository::class)
 */
class Offre
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Assert\NotBlank(message="Name should not be empty !!")
     * @Assert\Length(
     *     min=3,
     *     max= 20,
     *     minMessage ="Name should be >=3",
     *     maxMessage ="Name should be <=20")
     * @ORM\Column(type="string", length=255)
     */
    private $Nom;

    /**
     * @Assert\Range(
     *      min = 0,
     *      max = 100,
     *      notInRangeMessage = "You must be between {{ min }}% and {{ max }}% to enter",
     * )
     * @ORM\Column(type="integer")
     */
    private $Pourcentage;

    /**
     * @ORM\OneToMany(targetEntity=Evenement::class, mappedBy="Offre")
     */
    private $evenements;

    public function __construct()
    {
        $this->evenements = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getPourcentage(): ?string
    {
        return $this->Pourcentage;
    }

    public function setPourcentage(string $Pourcentage): self
    {
        $this->Pourcentage = $Pourcentage;

        return $this;
    }

    /**
     * @return Collection|Evenement[]
     */
    public function getEvenements(): Collection
    {
        return $this->evenements;
    }

    public function addEvenement(Evenement $evenement): self
    {
        if (!$this->evenements->contains($evenement)) {
            $this->evenements[] = $evenement;
            $evenement->setOffre($this);
        }

        return $this;
    }

    public function removeEvenement(Evenement $evenement): self
    {
        if ($this->evenements->removeElement($evenement)) {
            // set the owning side to null (unless already changed)
            if ($evenement->getOffre() === $this) {
                $evenement->setOffre(null);
            }
        }

        return $this;
    }
}
