<?php

namespace App\Entity;

use App\Repository\PieceRechangeRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=PieceRechangeRepository::class)
 * @UniqueEntity("designation", message="Cette piece existe déja ")
 */
class PieceRechange
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
    private $designation;

    /**
     * @ORM\Column(type="integer")
     */
    private $quantite;

    /**
     * @ORM\OneToMany(targetEntity=LigneBS::class, mappedBy="piece",cascade={"all"})
     */
    private $ligneBS;

    /**
     * @ORM\OneToMany(targetEntity=LigneBE::class, mappedBy="piece",cascade={"all"})
     */
    private $ligneBEs;

    /**
     * @ORM\OneToMany(targetEntity=PiecesMachine::class, mappedBy="piece")
     */
    private $piecesMachines;






    public function __construct()
    {
        $this->ligneBS = new ArrayCollection();
        $this->ligneBEs = new ArrayCollection();
        $this->piecesMachines = new ArrayCollection();

    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDesignation(): ?string
    {
        return $this->designation;
    }

    public function setDesignation(string $designation): self
    {
        $this->designation = $designation;

        return $this;
    }

    public function getQuantite(): ?int
    {
        return $this->quantite;
    }

    public function setQuantite(int $quantite): self
    {
        $this->quantite = $quantite;

        return $this;
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
            $ligneB->setPiece($this);
        }

        return $this;
    }

    public function removeLigneB(LigneBS $ligneB): self
    {
        if ($this->ligneBS->contains($ligneB)) {
            $this->ligneBS->removeElement($ligneB);
            // set the owning side to null (unless already changed)
            if ($ligneB->getPiece() === $this) {
                $ligneB->setPiece(null);
            }
        }

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
            $ligneBE->setPiece($this);
        }

        return $this;
    }

    public function removeLigneBE(LigneBE $ligneBE): self
    {
        if ($this->ligneBEs->contains($ligneBE)) {
            $this->ligneBEs->removeElement($ligneBE);
            // set the owning side to null (unless already changed)
            if ($ligneBE->getPiece() === $this) {
                $ligneBE->setPiece(null);
            }
        }

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
            $piecesMachine->setPiece($this);
        }

        return $this;
    }

    public function removePiecesMachine(PiecesMachine $piecesMachine): self
    {
        if ($this->piecesMachines->contains($piecesMachine)) {
            $this->piecesMachines->removeElement($piecesMachine);
            // set the owning side to null (unless already changed)
            if ($piecesMachine->getPiece() === $this) {
                $piecesMachine->setPiece(null);
            }
        }

        return $this;
    }









}
