<?php

namespace App\Entity;

use App\Repository\UsersRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UsersRepository::class)]
class Users
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    private ?string $prenom = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    private ?string $adresse = null;

    #[ORM\Column(length: 255)]
    private ?string $tel = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $birthdate = null;

    /**
     * @var Collection<int, Possessions>
     */
    #[ORM\OneToMany(targetEntity: Possessions::class, mappedBy: 'users')]
    private Collection $possession;

    public function __construct()
    {
        $this->possession = new ArrayCollection();
    }



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): static
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): static
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getTel(): ?string
    {
        return $this->tel;
    }

    public function setTel(string $tel): static
    {
        $this->tel = $tel;

        return $this;
    }

    public function getBirthdate(): ?\DateTimeInterface
    {
        return $this->birthdate;
    }

    public function setBirthdate(?\DateTimeInterface $birthdate): static
    {
        $this->birthdate = $birthdate;

        return $this;
    }

   
    public function getAge(): ?int
    {
        $birthdate = $this->birthdate;
        if ($birthdate instanceof DateTime) //si la propriété est une instance de la classe (native de php) datetime
        {
            $now = new DateTime();
            $interval = $now->diff($birthdate);
            return $interval->y; // Retourne l'âge en années
        }
        return null; // Retourne null si la date de naissance n'est pas valide
    }

    /**
     * @return Collection<int, Possessions>
     */
    public function getPossession(): Collection
    {
        return $this->possession;
    }

    public function addPossession(Possessions $possession): static
    {
        if (!$this->possession->contains($possession)) {
            $this->possession->add($possession);
            $possession->setUsers($this);
        }

        return $this;
    }

    public function removePossession(Possessions $possession): static
    {
        if ($this->possession->removeElement($possession)) {
            // set the owning side to null (unless already changed)
            if ($possession->getUsers() === $this) {
                $possession->setUsers(null);
            }
        }

        return $this;
    }
}
