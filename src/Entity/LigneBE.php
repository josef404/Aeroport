<?php

namespace App\Entity;

use App\Repository\LigneBERepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=LigneBERepository::class)
 */
class LigneBE
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=PieceRechange::class, inversedBy="ligneBEs")
     * @ORM\JoinColumn(nullable=false)
     */
    private $piece;

    /**
     * @ORM\Column(type="integer")
     */
    private $quantite;

    /**
     * @ORM\ManyToOne(targetEntity=BonEntre::class, inversedBy="ligneBEs", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $bonEntre;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPiece(): ?PieceRechange
    {
        return $this->piece;
    }

    public function setPiece(?PieceRechange $piece): self
    {
        $this->piece = $piece;

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

    public function getBonEntre(): ?BonEntre
    {
        return $this->bonEntre;
    }

    public function setBonEntre(?BonEntre $bonEntre): self
    {
        $this->bonEntre = $bonEntre;

        return $this;
    }
}
