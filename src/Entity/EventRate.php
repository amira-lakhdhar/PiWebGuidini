<?php

namespace App\Entity;

use App\Repository\EventRateRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EventRateRepository::class)
 */
class EventRate
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $id_user;

    /**
     * @ORM\ManyToOne(targetEntity=Evenement::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $id_Event;

    /**
     * @ORM\Column(type="integer")
     */
    private $Rate;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdUser(): ?User
    {
        return $this->id_user;
    }

    public function setIdUser(?User $id_user): self
    {
        $this->id_user = $id_user;

        return $this;
    }

    public function getIdEvent(): ?Evenement
    {
        return $this->id_Event;
    }

    public function setIdEvent(?Evenement $id_Event): self
    {
        $this->id_Event = $id_Event;

        return $this;
    }

    public function getRate(): ?int
    {
        return $this->Rate;
    }

    public function setRate(int $Rate): self
    {
        $this->Rate = $Rate;

        return $this;
    }
}
