<?php

namespace App\Entity;

use App\Repository\TechniciensRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
/**
 * @ORM\Entity(repositoryClass=TechniciensRepository::class)
 * @UniqueEntity("telephone", message="Ce numero existe déja ")
 */
class Techniciens
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

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
    private $dateNaissance;

    /**
     * @ORM\Column(type="date")
     */
    private $dateRecrutement;

    /**
     * @ORM\OneToOne(targetEntity=User::class, inversedBy="techniciens", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;



    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="techs")
     * @ORM\JoinColumn(nullable=false)
     */
    private $chef;

    /**
     * @ORM\ManyToMany(targetEntity=FicheIntervention::class, mappedBy="techniciens")
     */
    private $ficheInterventions;

    /**
     * @ORM\ManyToMany(targetEntity=DemandeIntervention::class, mappedBy="technicien")
     */
    private $demandeInterventions;

    public function __construct()
    {
        $this->ficheInterventions = new ArrayCollection();
        $this->demandeInterventions = new ArrayCollection();
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
    public function getPrenomNom(): ?string
    {
        return $this->prenom.' '.$this->nom;
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
        return $this->dateNaissance;
    }

    public function setDateNaissance(\DateTimeInterface $dateNaissance): self
    {
        $this->dateNaissance = $dateNaissance;

        return $this;
    }

    public function getDateRecrutement(): ?\DateTimeInterface
    {
        return $this->dateRecrutement;
    }

    public function setDateRecrutement(\DateTimeInterface $dateRecrutement): self
    {
        $this->dateRecrutement = $dateRecrutement;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }



    public function getChef(): ?User
    {
        return $this->chef;
    }

    public function setChef(?User $chef): self
    {
        $this->chef = $chef;

        return $this;
    }

    /**
     * @return Collection|FicheIntervention[]
     */
    public function getFicheInterventions(): Collection
    {
        return $this->ficheInterventions;
    }

    public function addFicheIntervention(FicheIntervention $ficheIntervention): self
    {
        if (!$this->ficheInterventions->contains($ficheIntervention)) {
            $this->ficheInterventions[] = $ficheIntervention;
            $ficheIntervention->addTechnicien($this);
        }

        return $this;
    }

    public function removeFicheIntervention(FicheIntervention $ficheIntervention): self
    {
        if ($this->ficheInterventions->contains($ficheIntervention)) {
            $this->ficheInterventions->removeElement($ficheIntervention);
            $ficheIntervention->removeTechnicien($this);
        }

        return $this;
    }

    /**
     * @return Collection|DemandeIntervention[]
     */
    public function getDemandeInterventions(): Collection
    {
        return $this->demandeInterventions;
    }

    public function addDemandeIntervention(DemandeIntervention $demandeIntervention): self
    {
        if (!$this->demandeInterventions->contains($demandeIntervention)) {
            $this->demandeInterventions[] = $demandeIntervention;
            $demandeIntervention->addTechnicien($this);
        }

        return $this;
    }

    public function removeDemandeIntervention(DemandeIntervention $demandeIntervention): self
    {
        if ($this->demandeInterventions->contains($demandeIntervention)) {
            $this->demandeInterventions->removeElement($demandeIntervention);
            $demandeIntervention->removeTechnicien($this);
        }

        return $this;
    }




}
