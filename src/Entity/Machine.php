<?php

namespace App\Entity;

use App\Repository\MachineRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=MachineRepository::class)
 * @UniqueEntity("libelle", message="Cette machine existe déja ")
 */
class Machine
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     */
    private $libelle;

    /**
     * @ORM\Column(type="integer")
     */
    private $num_serie;



    /**
     * @ORM\Column(type="date")
     */
    private $date_installation;



    /**
     * @ORM\ManyToOne(targetEntity=SousFamille::class, inversedBy="machines")
     * @ORM\JoinColumn(nullable=false)
     */
    private $sous_famille;







    /**
     * @ORM\OneToMany(targetEntity=FicheIntervention::class, mappedBy="machine")
     */
    private $ficheInterventions;

    /**
     * @ORM\OneToMany(targetEntity=Demontage::class, mappedBy="machine")
     */
    private $demontages;

    /**
     * @ORM\OneToMany(targetEntity=BonEntre::class, mappedBy="demontage")
     */
    private $bonEntres;

    /**
     * @ORM\OneToMany(targetEntity=DemandeIntervention::class, mappedBy="machine")
     */
    private $demandeInterventions;

    /**
     * @ORM\OneToMany(targetEntity=Entretient::class, mappedBy="machine")
     */
    private $entretients;

    /**
     * @ORM\Column(type="integer")
     */
    private $nbHeureTravail;



    /**
     * @ORM\ManyToOne(targetEntity=Batiment::class, inversedBy="machines")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank()
     */
    private $batiment;

    /**
     * @ORM\OneToMany(targetEntity=PiecesMachine::class, mappedBy="machine")
     */
    private $piecesMachines;









    public function __construct()
    {
        $this->ficheInterventions = new ArrayCollection();
        $this->demontages = new ArrayCollection();
        $this->bonEntres = new ArrayCollection();
        $this->demandeInterventions = new ArrayCollection();
        $this->entretients = new ArrayCollection();
        $this->piecesMachines = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): self
    {
        $this->libelle = $libelle;

        return $this;
    }

    public function getNumSerie(): ?int
    {
        return $this->num_serie;
    }

    public function setNumSerie(int $num_serie): self
    {
        $this->num_serie = $num_serie;

        return $this;
    }



    public function getDateInstallation(): ?\DateTimeInterface
    {
        return $this->date_installation;
    }

    public function setDateInstallation(\DateTimeInterface $date_installation): self
    {
        $this->date_installation = $date_installation;

        return $this;
    }



    public function getSousFamille(): ?SousFamille
    {
        return $this->sous_famille;
    }

    public function setSousFamille(?SousFamille $sous_famille): self
    {
        $this->sous_famille = $sous_famille;

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
            $ficheIntervention->setMachine($this);
        }

        return $this;
    }

    public function removeFicheIntervention(FicheIntervention $ficheIntervention): self
    {
        if ($this->ficheInterventions->contains($ficheIntervention)) {
            $this->ficheInterventions->removeElement($ficheIntervention);
            // set the owning side to null (unless already changed)
            if ($ficheIntervention->getMachine() === $this) {
                $ficheIntervention->setMachine(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Demontage[]
     */
    public function getDemontages(): Collection
    {
        return $this->demontages;
    }

    public function addDemontage(Demontage $demontage): self
    {
        if (!$this->demontages->contains($demontage)) {
            $this->demontages[] = $demontage;
            $demontage->setMachine($this);
        }

        return $this;
    }

    public function removeDemontage(Demontage $demontage): self
    {
        if ($this->demontages->contains($demontage)) {
            $this->demontages->removeElement($demontage);
            // set the owning side to null (unless already changed)
            if ($demontage->getMachine() === $this) {
                $demontage->setMachine(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|BonEntre[]
     */
    public function getBonEntres(): Collection
    {
        return $this->bonEntres;
    }

    public function addBonEntre(BonEntre $bonEntre): self
    {
        if (!$this->bonEntres->contains($bonEntre)) {
            $this->bonEntres[] = $bonEntre;
            $bonEntre->setDemontage($this);
        }

        return $this;
    }

    public function removeBonEntre(BonEntre $bonEntre): self
    {
        if ($this->bonEntres->contains($bonEntre)) {
            $this->bonEntres->removeElement($bonEntre);
            // set the owning side to null (unless already changed)
            if ($bonEntre->getDemontage() === $this) {
                $bonEntre->setDemontage(null);
            }
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
            $demandeIntervention->setMachine($this);
        }

        return $this;
    }

    public function removeDemandeIntervention(DemandeIntervention $demandeIntervention): self
    {
        if ($this->demandeInterventions->contains($demandeIntervention)) {
            $this->demandeInterventions->removeElement($demandeIntervention);
            // set the owning side to null (unless already changed)
            if ($demandeIntervention->getMachine() === $this) {
                $demandeIntervention->setMachine(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Entretient[]
     */
    public function getEntretients(): Collection
    {
        return $this->entretients;
    }

    public function addEntretient(Entretient $entretient): self
    {
        if (!$this->entretients->contains($entretient)) {
            $this->entretients[] = $entretient;
            $entretient->setMachine($this);
        }

        return $this;
    }

    public function removeEntretient(Entretient $entretient): self
    {
        if ($this->entretients->contains($entretient)) {
            $this->entretients->removeElement($entretient);
            // set the owning side to null (unless already changed)
            if ($entretient->getMachine() === $this) {
                $entretient->setMachine(null);
            }
        }

        return $this;
    }

    public function getNbHeureTravail(): ?int
    {
        return $this->nbHeureTravail;
    }

    public function setNbHeureTravail(int $nbHeureTravail): self
    {
        $this->nbHeureTravail = $nbHeureTravail;

        return $this;
    }



    public function getBatiment(): ?Batiment
    {
        return $this->batiment;
    }

    public function setBatiment(?Batiment $batiment): self
    {
        $this->batiment = $batiment;

        return $this;
    }

    /**
     * @return Collection|PiecesMachine[]
     */
    public function getPiecesMachines(): Collection
    {
        return $this->piecesMachines;
    }

    public function addPiecesMachine(PiecesMachine $piecesMachine): self
    {
        if (!$this->piecesMachines->contains($piecesMachine)) {
            $this->piecesMachines[] = $piecesMachine;
            $piecesMachine->setMachine($this);
        }

        return $this;
    }

    public function removePiecesMachine(PiecesMachine $piecesMachine): self
    {
        if ($this->piecesMachines->contains($piecesMachine)) {
            $this->piecesMachines->removeElement($piecesMachine);
            // set the owning side to null (unless already changed)
            if ($piecesMachine->getMachine() === $this) {
                $piecesMachine->setMachine(null);
            }
        }

        return $this;
    }




}
