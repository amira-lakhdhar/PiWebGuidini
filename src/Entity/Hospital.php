<?php

namespace App\Entity;

use App\Repository\HosiptalRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass=HosiptalRepository::class)
 */
class Hospital
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
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $location;

    /**
     * @Assert\NotBlank(message="Score should not be Null")
     * @ORM\Column(type="integer")
     */
    private $score;

    /**
     * @Assert\NotBlank(message="Phone should not be empty")
     *  @Assert\Length(
     *     min = 8,
     *     max = 8,
     *     minMessage= "Phone number is too short",
     *     maxMessage="Phone number is too long")
     * @ORM\Column(type="string", length=255)
     */
    private $phone;

    /**
     * @Assert\NotBlank(message="Email should not be empty")
     * @Assert\Email(message="Your email should be exp@gmail.com")
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $email;

    /**
     * @ORM\OneToMany(targetEntity=Doctor::class, mappedBy="id_hospital")
     */
    private $doctors;




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

    public function setLocation(?string $location): self
    {
        $this->location = $location;

        return $this;
    }

    public function getScore(): ?int
    {
        return $this->score;
    }

    public function setScore(int $score): self
    {
        $this->score = $score;

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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return Collection|Doctor[]
     */
    public function getDoctors(): Collection
    {
        return $this->doctors;
    }

    public function addDoctor(Doctor $doctor): self
    {
        if (!$this->doctors->contains($doctor)) {
            $this->doctors[] = $doctor;
            $doctor->setIdHospital($this);
        }

        return $this;
    }

    public function removeDoctor(Doctor $doctor): self
    {
        if ($this->doctors->removeElement($doctor)) {
            // set the owning side to null (unless already changed)
            if ($doctor->getIdHospital() === $this) {
                $doctor->setIdHospital(null);
            }
        }

        return $this;
    }
}
