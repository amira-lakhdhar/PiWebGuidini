<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity(
 *     fields={"email"},
 *     message="L'email que vous avais indiquÃŠ  est dÃŠjÃ  utilisÃŠ !"
 * )
 */
class User
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
     * @Assert\Length(
     *     min=3,
     *     max= 20,
     *     minMessage ="Name should be >=3",
     *     maxMessage ="Name should be <=20")
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="prenom should not be empty !!")
     * @Assert\Length(
     *     min=3,
     *     max= 20,
     *     minMessage ="Name should be >=3",
     *     maxMessage ="Name should be <=20")
     */
    private $prenom;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="adresse should not be empty !!")
     *     maxMessage ="Name should be <=20")
     */
    private $adresse;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(min="8" ,  minMessage="Votre mot de passe doit faire minimum 8 caracters")
     */
    private $password;

    /**
     * @Assert\EqualTo(propertyPath="Password",message="les deux mot de passe doit etre le meme")
     */
    public $Confirm_Password;

    /**
     * @ORM\Column(type="boolean")
     */
    private $role;



    public function __construct()
    {
        $this->reclamations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getRole(): ?bool
    {
        return $this->role;
    }

    public function setRole(bool $role): self
    {
        $this->role = $role;

        return $this;
    }


    public function getConfirm_Password(): ?string
    {
        return $this->Confirm_Password;
    }

    public function setConfirm_Password(string $Confirm_Password): self
    {
        $this->Confirm_Password = $Confirm_Password;

        return $this;
    }
}