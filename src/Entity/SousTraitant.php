<?php

namespace App\Entity;

use App\Repository\SousTraitantRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=SousTraitantRepository::class)
 * @UniqueEntity("numTel", message="Ce numero existe déja ")
 */
class SousTraitant
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
     * @ORM\Column(type="integer")
     */
    private $numTel;

    /**
     * @ORM\Column(type="date")
     */
    private $date_debut_contrat;

    /**
     * @ORM\Column(type="date")
     */
    private $date_fin_contrat;

    /**
     * @ORM\OneToMany(targetEntity=DemandeIntervention::class, mappedBy="sous_traitant")
     */
    private $demandeInterventions;

    /**
     * @ORM\OneToMany(targetEntity=FicheIntervention::class, mappedBy="soutraitant")
     */
    private $ficheInterventions;

    public function __construct()
    {
        $this->demandeInterventions = new ArrayCollection();
        $this->ficheInterventions = new ArrayCollection();
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

    public function getNumTel(): ?int
    {
        return $this->numTel;
    }

    public function setNumTel(int $numTel): self
    {
        $this->numTel = $numTel;

        return $this;
    }

    public function getDateDebutContrat(): ?\DateTimeInterface
    {
        return $this->date_debut_contrat;
    }

    public function setDateDebutContrat(\DateTimeInterface $date_debut_contrat): self
    {
        $this->date_debut_contrat = $date_debut_contrat;

        return $this;
    }

    public function getDateFinContrat(): ?\DateTimeInterface
    {
        return $this->date_fin_contrat;
    }

    public function setDateFinContrat(\DateTimeInterface $date_fin_contrat): self
    {
        $this->date_fin_contrat = $date_fin_contrat;

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
            $demandeIntervention->setSousTraitant($this);
        }

        return $this;
    }

    public function removeDemandeIntervention(DemandeIntervention $demandeIntervention): self
    {
        if ($this->demandeInterventions->contains($demandeIntervention)) {
            $this->demandeInterventions->removeElement($demandeIntervention);
            // set the owning side to null (unless already changed)
            if ($demandeIntervention->getSousTraitant() === $this) {
                $demandeIntervention->setSousTraitant(null);
            }
        }

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
            $ficheIntervention->setSoutraitant($this);
        }

        return $this;
    }

    public function removeFicheIntervention(FicheIntervention $ficheIntervention): self
    {
        if ($this->ficheInterventions->contains($ficheIntervention)) {
            $this->ficheInterventions->removeElement($ficheIntervention);
            // set the owning side to null (unless already changed)
            if ($ficheIntervention->getSoutraitant() === $this) {
                $ficheIntervention->setSoutraitant(null);
            }
        }

        return $this;
    }
}
