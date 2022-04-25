<?php

namespace App\Entity;

use App\Repository\PharmacyRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass=PharmacyRepository::class)
 */
class Pharmacy
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
     *     minMessage ="Pharmacy Name should be >=3")
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @Assert\NotBlank(message="location should not be empty")
     * @ORM\Column(type="string", length=255)
     */
    private $location;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $hourly;

    /**
     * @Assert\Length(
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
     * @ORM\Column(type="string", length=255)
     */
    private $email;

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

    public function getHourly(): ?string
    {
        return $this->hourly;
    }

    public function setHourly(string $hourly): self
    {
        $this->hourly = $hourly;

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

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }
}
