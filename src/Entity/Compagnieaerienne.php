<?php

namespace App\Entity;

use App\Repository\CompagnieaerienneRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass=CompagnieaerienneRepository::class)
 */
class Compagnieaerienne
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Assert\NotBlank(message="Name should not be empty")
     * @Assert\Length(
     *     min=3,
     *     minMessage ="Le nom de la Compagnie est trop court")
     * @ORM\Column(type="string", length=255)
     */
    private $Nom;

    /**
     * @Assert\NotBlank(message="Email should not be empty")
     * @Assert\Email(message="l'email doit avoir cette forme exp@gmail.com")
     * @ORM\Column(type="string", length=255)
     */
    private $Email;

    /**
     * @Assert\NotBlank(message="Contact should not be empty")
     * @Assert\Length(
     *     min=3,
     *     minMessage ="Le Contact de la Compagnie est trop court")
     * @ORM\Column(type="string", length=255)
     */
    private $Contact;

    /**
     * @ORM\OneToMany(targetEntity=Vol::class, mappedBy="Compagnie")
     */
    private $vols;

    /**
     * @Assert\NotBlank(message="Logo should not be empty")
     * @ORM\Column(type="string", length=255)
     */
    private $Logo;

    public function __construct()
    {
        $this->vols = new ArrayCollection();
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

    public function getEmail(): ?string
    {
        return $this->Email;
    }

    public function setEmail(string $Email): self
    {
        $this->Email = $Email;

        return $this;
    }

    public function getContact(): ?string
    {
        return $this->Contact;
    }

    public function setContact(string $Contact): self
    {
        $this->Contact = $Contact;

        return $this;
    }

    /**
     * @return Collection|Vol[]
     */
    public function getVols(): Collection
    {
        return $this->vols;
    }

    public function addVol(Vol $vol): self
    {
        if (!$this->vols->contains($vol)) {
            $this->vols[] = $vol;
            $vol->setCompagnie($this);
        }

        return $this;
    }

    public function removeVol(Vol $vol): self
    {
        if ($this->vols->removeElement($vol)) {
            // set the owning side to null (unless already changed)
            if ($vol->getCompagnie() === $this) {
                $vol->setCompagnie(null);
            }
        }

        return $this;
    }

    public function getLogo(): ?string
    {
        return $this->Logo;
    }

    public function setLogo(string $Logo): self
    {
        $this->Logo = $Logo;

        return $this;
    }
}
