<?php

namespace App\Entity;

use App\Repository\EvenementRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use phpDocumentor\Reflection\Types\Boolean;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass=EvenementRepository::class)
 */
class Evenement
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Assert\Date()
     * @Assert\GreaterThan("today",message="Date is not valid")
     * @ORM\Column(type="datetime")
     */
    private $Date;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Type;

    /**
     * @Assert\NotBlank(message="Location is required")
     * @ORM\Column(type="string", length=255)
     */
    private $localisation;

    /**
     * @Assert\NotBlank(message="Image is required")
     * @ORM\Column(type="string", length=255)
     */
    private $Image;

    /**
     * @ORM\OneToMany(targetEntity=Reservation::class, mappedBy="id_evenement")
     */
    private $reservations;

    /**
     * @Assert\Range(
     *      min = 0,
     *      notInRangeMessage = "You must be positive ",
     * )
     * @ORM\Column(type="float")
     */
    private $Prix;

    /**
     * @Assert\NotBlank(message="Nom should not be empty !!")
     * @Assert\Length(
     *     min=5,
     *     max= 20,
     *     minMessage ="Name should be >=5",
     *     maxMessage ="Name should be <=20")
     * @ORM\Column(type="string", length=255)
     */
    private $Nom;

    /**
     * @ORM\ManyToOne(targetEntity=Offre::class, inversedBy="evenements")
     */
    private $Offre;

    /**
     * @Assert\Length(
     *     min=10,
     *     max=255,
     *     minMessage ="Description should be >=10")
     * )
     * @ORM\Column(type="string", length=255)
     */
    private $Description;

    private $isReserved;

    public function __construct()
    {
        $this->reservations = new ArrayCollection();
    }




    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->Date;
    }

    public function setDate(\DateTimeInterface $Date): self
    {
        $this->Date = $Date;

        return $this;
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
     public function getReserved(): ?int
     {
         return $this->isReserved;
     }

    public function setReserved(int $isReserved): self
    {
        $this->isReserved = $isReserved;

        return $this;
    }

    public function getLocalisation(): ?string
    {
        return $this->localisation;
    }

    public function setLocalisation(string $localisation): self
    {
        $this->localisation = $localisation;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->Image;
    }

    public function setImage(string $Image): self
    {
        $this->Image = $Image;

        return $this;
    }

    /**
     * @return Collection|Reservation[]
     */
    public function getReservations(): Collection
    {
        return $this->reservations;
    }

    public function addReservation(Reservation $reservation): self
    {
        if (!$this->reservations->contains($reservation)) {
            $this->reservations[] = $reservation;
            $reservation->setIdEvenement($this);
        }

        return $this;
    }

    public function removeReservation(Reservation $reservation): self
    {
        if ($this->reservations->removeElement($reservation)) {
            // set the owning side to null (unless already changed)
            if ($reservation->getIdEvenement() === $this) {
                $reservation->setIdEvenement(null);
            }
        }

        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->Prix;
    }

    public function setPrix(float $Prix): self
    {
        $this->Prix = $Prix;

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

    public function getOffre(): ?Offre
    {
        return $this->Offre;
    }

    public function setOffre(?Offre $Offre): self
    {
        $this->Offre = $Offre;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->Description;
    }

    public function setDescription(string $Description): self
    {
        $this->Description = $Description;

        return $this;
    }


}
