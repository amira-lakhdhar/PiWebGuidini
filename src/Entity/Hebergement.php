<?php

namespace App\Entity;

use App\Repository\HebergementRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=HebergementRepository::class)
 */
class Hebergement
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="nom should not be empty !!")
     */
    private $type;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="nom should not be empty !!")
     * @Assert\Length(
     *     min=3,
     *     max= 20,
     *     minMessage ="Name should be >=3",
     *     maxMessage ="Name should be <=20")
     *
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(
     *     min=3,
     *     max= 20,
     *     minMessage ="Name should be >=3",
     *     maxMessage ="Name should be <=20")
     */
    private $adresse;

    /**
     * @ORM\Column(type="float")
     * @Assert\Positive
     */
    private $prixParNuit;

    private $isReserved;
    private $Reservation;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Photo;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

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

    public function getPrixParNuit(): ?float
    {
        return $this->prixParNuit;
    }

    public function setPrixParNuit(float $prixParNuit): self
    {
        $this->prixParNuit = $prixParNuit;

        return $this;
    }
    /**
     * @return mixed
     */
    public function getIsReserved()
    {
        return $this->isReserved;
    }

    /**
     * @param mixed $isReserved
     */
    public function setIsReserved($isReserved): void
    {
        $this->isReserved = $isReserved;
    }

    /**
     * @return mixed
     */
    public function getReservation()
    {
        return $this->Reservation;
    }

    /**
     * @param mixed $Reservation
     */
    public function setReservation($Reservation): void
    {
        $this->Reservation = $Reservation;
    }

    public function getPhoto(): ?string
    {
        return $this->Photo;
    }

    public function setPhoto(string $Photo): self
    {
        $this->Photo = $Photo;

        return $this;
    }

    
}
