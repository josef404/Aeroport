<?php

namespace App\Entity;

use App\Repository\FicheInterventionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=FicheInterventionRepository::class)
 */
class FicheIntervention
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Machine::class, inversedBy="ficheInterventions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $machine;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date_declaration;



    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $date_debut;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $date_fin;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;



    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $activation;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $numero_intervention;

    /**
     * @ORM\OneToMany(targetEntity=BonSortie::class, mappedBy="intervention", cascade={"all"})
     */
    private $bonSorties;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $tech;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $reference;

    /**
     * @ORM\ManyToOne(targetEntity=SousTraitant::class, inversedBy="ficheInterventions")
     */
    private $soutraitant;

    /**
     * @ORM\ManyToMany(targetEntity=Techniciens::class, inversedBy="ficheInterventions")
     */
    private $techniciens;





    public function __construct()
    {
        $this->bonSorties = new ArrayCollection();
        $this->techniciens = new ArrayCollection();
    }




    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMachine(): ?Machine
    {
        return $this->machine;
    }

    public function setMachine(?Machine $machine): self
    {
        $this->machine = $machine;

        return $this;
    }

    public function getDateDeclaration(): ?\DateTimeInterface
    {
        return $this->date_declaration;
    }

    public function setDateDeclaration(\DateTimeInterface $date_declaration): self
    {
        $this->date_declaration = $date_declaration;

        return $this;
    }



    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->date_debut;
    }

    public function setDateDebut(?\DateTimeInterface $date_debut): self
    {
        $this->date_debut = $date_debut;

        return $this;
    }

    public function getDateFin(): ?\DateTimeInterface
    {
        return $this->date_fin;
    }

    public function setDateFin(?\DateTimeInterface $date_fin): self
    {
        $this->date_fin = $date_fin;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }



    public function getActivation(): ?bool
    {
        return $this->activation;
    }

    public function setActivation(?bool $activation): self
    {
        $this->activation = $activation;

        return $this;
    }

    public function getNumeroIntervention(): ?int
    {
        return $this->numero_intervention;
    }

    public function setNumeroIntervention(int $numero_intervention): self
    {
        $this->numero_intervention = $numero_intervention;

        return $this;
    }

    /**
     * @return Collection|BonSortie[]
     */
    public function getBonSorties(): Collection
    {
        return $this->bonSorties;
    }

    public function addBonSorty(BonSortie $bonSorty): self
    {
        if (!$this->bonSorties->contains($bonSorty)) {
            $this->bonSorties[] = $bonSorty;
            $bonSorty->setIntervention($this);
        }

        return $this;
    }

    public function removeBonSorty(BonSortie $bonSorty): self
    {
        if ($this->bonSorties->contains($bonSorty)) {
            $this->bonSorties->removeElement($bonSorty);
            // set the owning side to null (unless already changed)
            if ($bonSorty->getIntervention() === $this) {
                $bonSorty->setIntervention(null);
            }
        }

        return $this;
    }

    public function getTech(): ?User
    {
        return $this->tech;
    }

    public function setTech(?User $tech): self
    {
        $this->tech = $tech;

        return $this;
    }

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(string $reference): self
    {
        $this->reference = $reference;

        return $this;
    }

    public function getSoutraitant(): ?SousTraitant
    {
        return $this->soutraitant;
    }

    public function setSoutraitant(?SousTraitant $soutraitant): self
    {
        $this->soutraitant = $soutraitant;

        return $this;
    }

    /**
     * @return Collection|Techniciens[]
     */
    public function getTechniciens(): Collection
    {
        return $this->techniciens;
    }

    public function addTechnicien(Techniciens $technicien): self
    {
        if (!$this->techniciens->contains($technicien)) {
            $this->techniciens[] = $technicien;
        }

        return $this;
    }

    public function removeTechnicien(Techniciens $technicien): self
    {
        if ($this->techniciens->contains($technicien)) {
            $this->techniciens->removeElement($technicien);
        }

        return $this;
    }





}
