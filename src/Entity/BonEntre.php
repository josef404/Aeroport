<?php

namespace App\Entity;

use App\Repository\BonEntreRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
/**
 * @ORM\Entity(repositoryClass=BonEntreRepository::class)
 */
class BonEntre
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;



    /**
     * @ORM\Column(type="date")
     */
    private $date;





    /**
     * @ORM\ManyToOne(targetEntity=Fournisseur::class, inversedBy="bonEntres")
     * @Assert\NotBlank(allowNull = true)
     */
    private $fournisseur;

    /**
     * @ORM\ManyToOne(targetEntity=Machine::class, inversedBy="bonEntres")
     * @Assert\NotBlank(allowNull = true)
     */
    private $demontage;



    /**
     * @ORM\OneToMany(targetEntity=LigneBE::class, mappedBy="bonEntre", cascade={"persist", "remove"})
     */
    private $ligneBEs;

    public function __construct()
    {
        $this->ligneBEs = new ArrayCollection();
    }




    public function getId(): ?int
    {
        return $this->id;
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





    public function getFournisseur(): ?Fournisseur
    {
        return $this->fournisseur;
    }

    public function setFournisseur(?Fournisseur $fournisseur): self
    {
        $this->fournisseur = $fournisseur;

        return $this;
    }

    public function getDemontage(): ?Machine
    {
        return $this->demontage;
    }

    public function setDemontage(?Machine $demontage): self
    {
        $this->demontage = $demontage;

        return $this;
    }



    /**
     * @return Collection|LigneBE[]
     */
    public function getLigneBEs(): Collection
    {
        return $this->ligneBEs;
    }

    public function addLigneBE(LigneBE $ligneBE): self
    {
        if (!$this->ligneBEs->contains($ligneBE)) {
            $this->ligneBEs[] = $ligneBE;
            $ligneBE->setBonEntre($this);
        }

        return $this;
    }

    public function removeLigneBE(LigneBE $ligneBE): self
    {
        if ($this->ligneBEs->contains($ligneBE)) {
            $this->ligneBEs->removeElement($ligneBE);
            // set the owning side to null (unless already changed)
            if ($ligneBE->getBonEntre() === $this) {
                $ligneBE->setBonEntre(null);
            }
        }

        return $this;
    }




}
