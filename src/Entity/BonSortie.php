<?php

namespace App\Entity;

use App\Repository\BonSortieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=BonSortieRepository::class)
 */
class BonSortie
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;



    /**
     * @ORM\OneToMany(targetEntity=LigneBS::class, mappedBy="bon_sortie", cascade={"all"})
     */
    private $ligneBS;

    /**
     * @ORM\ManyToOne(targetEntity=FicheIntervention::class, inversedBy="bonSorties")
     * @ORM\JoinColumn(nullable=false)
     */
    private $intervention;

    public function __construct()
    {
        $this->ligneBS = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }


    /**
     * @return Collection|LigneBS[]
     */
    public function getLigneBS(): Collection
    {
        return $this->ligneBS;
    }

    public function addLigneB(LigneBS $ligneB): self
    {
        if (!$this->ligneBS->contains($ligneB)) {
            $this->ligneBS[] = $ligneB;
            $ligneB->setBonSortie($this);
        }

        return $this;
    }

    public function removeLigneB(LigneBS $ligneB): self
    {
        if ($this->ligneBS->contains($ligneB)) {
            $this->ligneBS->removeElement($ligneB);
            // set the owning side to null (unless already changed)
            if ($ligneB->getBonSortie() === $this) {
                $ligneB->setBonSortie(null);
            }
        }

        return $this;
    }

    public function getIntervention(): ?FicheIntervention
    {
        return $this->intervention;
    }

    public function setIntervention(?FicheIntervention $intervention): self
    {
        $this->intervention = $intervention;

        return $this;
    }


}
