<?php

namespace App\Entity;

use App\Repository\DemandeInterventionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DemandeInterventionRepository::class)
 */
class HistoriqueDemande
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Machine::class, inversedBy="demandeInterventions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $machine;

    /**
     * @ORM\Column(type="date")
     */
    private $date;

    /**
     * @ORM\Column(type="text")
     */
    private $description;



    /**
     * @ORM\ManyToOne(targetEntity=SousTraitant::class, inversedBy="demandeInterventions")
     */
    private $sous_traitant;

    /**
     * @ORM\ManyToMany(targetEntity=Techniciens::class, inversedBy="demandeInterventions")
     */
    private $technicien;


    public function __construct()
    {
        $this->technicien = new ArrayCollection();
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

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }



    public function getSousTraitant(): ?SousTraitant
    {
        return $this->sous_traitant;
    }

    public function setSousTraitant(?SousTraitant $sous_traitant): self
    {
        $this->sous_traitant = $sous_traitant;

        return $this;
    }


    /**
     * @return Collection|Techniciens[]
     */
    public function getTechnicien(): Collection
    {
        return $this->technicien;
    }

    public function addTechnicien(Techniciens $technicien): self
    {
        if (!$this->technicien->contains($technicien)) {
            $this->technicien[] = $technicien;
        }

        return $this;
    }

    public function removeTechnicien(Techniciens $technicien): self
    {
        if ($this->technicien->contains($technicien)) {
            $this->technicien->removeElement($technicien);
        }

        return $this;
    }

}
