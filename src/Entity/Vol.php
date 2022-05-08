<?php

namespace App\Entity;

use App\Repository\VolRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass=VolRepository::class)
 */
class Vol
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Assert\NotBlank(message="Numero de vol is required")
     * @ORM\Column(type="integer")
     */
    private $num_vol;

    /**
     * @Assert\Date()
     * @Assert\GreaterThan("today",message="Date is not valid")
     * @ORM\Column(type="datetime")
     */
    private $date_vol;

    /**
     * @Assert\NotBlank(message="Destination is required")
     * @ORM\Column(type="string", length=255)
     */
    private $destination;

    /**
     * @Assert\NotBlank(message="Ville de Depart is required")
     * @ORM\Column(type="string", length=255)
     */
    private $ville_depart;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $type_vol;

    /**
     * @ORM\ManyToOne(targetEntity=Compagnieaerienne::class, inversedBy="vols")
     * @ORM\JoinColumn(nullable=false)
     */
    private $Compagnie;

    /**
     * @ORM\OneToMany(targetEntity=Voyage::class, mappedBy="Vol")
     */
    private $voyages;



    public function __construct()
    {
        $this->voyages = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumVol(): ?int
    {
        return $this->num_vol;
    }

    public function setNumVol(int $num_vol): self
    {
        $this->num_vol = $num_vol;

        return $this;
    }

    public function getDateVol(): ?\DateTimeInterface
    {
        return $this->date_vol;
    }

    public function setDateVol(\DateTimeInterface $date_vol): self
    {
        $this->date_vol = $date_vol;

        return $this;
    }

    public function getDestination(): ?string
    {
        return $this->destination;
    }

    public function setDestination(string $destination): self
    {
        $this->destination = $destination;

        return $this;
    }

    public function getVilleDepart(): ?string
    {
        return $this->ville_depart;
    }

    public function setVilleDepart(string $ville_depart): self
    {
        $this->ville_depart = $ville_depart;

        return $this;
    }

    public function getTypeVol(): ?string
    {
        return $this->type_vol;
    }

    public function setTypeVol(string $type_vol): self
    {
        $this->type_vol = $type_vol;

        return $this;
    }

    public function getCompagnie(): ?Compagnieaerienne
    {
        return $this->Compagnie;
    }

    public function setCompagnie(?Compagnieaerienne $Compagnie): self
    {
        $this->Compagnie = $Compagnie;

        return $this;
    }

    /**
     * @return Collection|Voyage[]
     */
    public function getVoyages(): Collection
    {
        return $this->voyages;
    }

    public function addVoyage(Voyage $voyage): self
    {
        if (!$this->voyages->contains($voyage)) {
            $this->voyages[] = $voyage;
            $voyage->setVol($this);
        }

        return $this;
    }

    public function removeVoyage(Voyage $voyage): self
    {
        if ($this->voyages->removeElement($voyage)) {
            // set the owning side to null (unless already changed)
            if ($voyage->getVol() === $this) {
                $voyage->setVol(null);
            }
        }

        return $this;
    }


}
