<?php

namespace App\Entity;

use App\Repository\DoctorRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=DoctorRepository::class)
 */
class Doctor
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
     *     max= 30,
     *     minMessage ="Name should be >=3")
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @Assert\NotBlank(message="location should not be empty")
     * @ORM\Column(type="string", length=255)
     */
    private $location;

    /**
     * @Assert\NotBlank(message="Speciality should not be empty")
     * @ORM\Column(type="string", length=255)
     */
    private $speciality;

    /**
     * @Assert\NotBlank(message="Phone should not be empty")
     * @Assert\Length(
     *     min = 8,
     *     max = 8,
     *     minMessage= "Phone number is too short",
     *     maxMessage="Phone number is too long")
     * @ORM\Column(type="integer")
     */
    private $phone;

    /**
     * @Assert\NotBlank(message="Image should not be empty")
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $Image;

    /**
     * @ORM\ManyToOne(targetEntity=Hospital::class, inversedBy="doctors")
     */
    private $id_hospital;

    /**
     * @ORM\OneToMany(targetEntity=RateDoctor::class, mappedBy="id_Doctor")
     */
    private $rateDoctors;

    public function __construct()
    {
        $this->rateDoctors = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(string $location): self
    {
        $this->location = $location;

        return $this;
    }

    public function getSpeciality(): ?string
    {
        return $this->speciality;
    }

    public function setSpeciality(string $speciality): self
    {
        $this->speciality = $speciality;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->Image;
    }

    public function setImage(?string $Image): self
    {
        $this->Image = $Image;

        return $this;
    }

    public function getIdHospital(): ?Hospital
    {
        return $this->id_hospital;
    }

    public function setIdHospital(?Hospital $id_hospital): self
    {
        $this->id_hospital = $id_hospital;

        return $this;
    }

    /**
     * @return Collection|RateDoctor[]
     */
    public function getRateDoctors(): Collection
    {
        return $this->rateDoctors;
    }

    public function addRateDoctor(RateDoctor $rateDoctor): self
    {
        if (!$this->rateDoctors->contains($rateDoctor)) {
            $this->rateDoctors[] = $rateDoctor;
            $rateDoctor->setIdDoctor($this);
        }

        return $this;
    }

    public function removeRateDoctor(RateDoctor $rateDoctor): self
    {
        if ($this->rateDoctors->removeElement($rateDoctor)) {
            // set the owning side to null (unless already changed)
            if ($rateDoctor->getIdDoctor() === $this) {
                $rateDoctor->setIdDoctor(null);
            }
        }

        return $this;
    }
}
