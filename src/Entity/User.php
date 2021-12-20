<?php
// src/Entity/User.php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use phpDocumentor\Reflection\Types\Parent_;

/**
 * @ORM\Entity
 * @ORM\Table(name="user")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $prenom;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $adress;

    /**
     * @ORM\Column(type="integer")
     */
    private $telephone;

    /**
     * @ORM\Column(type="date")
     */
    private $date_naissance;

    /**
     * @ORM\Column(type="date")
     */
    private $date_recrutement;

    /**
     * @ORM\OneToMany(targetEntity=DemandeIntervention::class, mappedBy="technicien")
     */
    private $demandeInterventions;

    /**
     * @ORM\OneToOne(targetEntity=Techniciens::class, mappedBy="user", cascade={"persist", "remove"})
     */
    private $techniciens;

    /**
     * @ORM\OneToOne(targetEntity=Magasiniers::class, mappedBy="user", cascade={"persist", "remove"})
     */
    private $magasiniers;

    /**
     * @ORM\OneToMany(targetEntity=Techniciens::class, mappedBy="chef")
     */
    private $techs;





    public function __construct()
    {
        parent::__construct();
        // your own logic
        $this->demandeInterventions = new ArrayCollection();
        $this->techs = new ArrayCollection();
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

    public function getAdress(): ?string
    {
        return $this->adress;
    }

    public function setAdress(string $adress): self
    {
        $this->adress = $adress;

        return $this;
    }

    public function getTelephone(): ?int
    {
        return $this->telephone;
    }

    public function setTelephone(int $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getDateNaissance(): ?\DateTimeInterface
    {
        return $this->date_naissance;
    }

    public function setDateNaissance(\DateTimeInterface $date_naissance): self
    {
        $this->date_naissance = $date_naissance;

        return $this;
    }

    public function getDateRecrutement(): ?\DateTimeInterface
    {
        return $this->date_recrutement;
    }

    public function setDateRecrutement(\DateTimeInterface $date_recrutement): self
    {
        $this->date_recrutement = $date_recrutement;

        return $this;
    }



    public function getTechniciens(): ?Techniciens
    {
        return $this->techniciens;
    }

    public function setTechniciens(Techniciens $techniciens): self
    {
        $this->techniciens = $techniciens;

        // set the owning side of the relation if necessary
        if ($techniciens->getUser() !== $this) {
            $techniciens->setUser($this);
        }

        return $this;
    }

    public function getMagasiniers(): ?Magasiniers
    {
        return $this->magasiniers;
    }

    public function setMagasiniers(Magasiniers $magasiniers): self
    {
        $this->magasiniers = $magasiniers;

        // set the owning side of the relation if necessary
        if ($magasiniers->getUser() !== $this) {
            $magasiniers->setUser($this);
        }

        return $this;
    }

    /**
     * @return Collection|Techniciens[]
     */
    public function getTechs(): Collection
    {
        return $this->techs;
    }

    public function addTech(Techniciens $tech): self
    {
        if (!$this->techs->contains($tech)) {
            $this->techs[] = $tech;
            $tech->setChef($this);
        }

        return $this;
    }

    public function removeTech(Techniciens $tech): self
    {
        if ($this->techs->contains($tech)) {
            $this->techs->removeElement($tech);
            // set the owning side to null (unless already changed)
            if ($tech->getChef() === $this) {
                $tech->setChef(null);
            }
        }

        return $this;
    }




}