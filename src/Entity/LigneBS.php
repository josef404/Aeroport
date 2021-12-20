<?php

namespace App\Entity;

use App\Repository\LigneBSRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=LigneBSRepository::class)
 */
class LigneBS
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=BonSortie::class, inversedBy="ligneBS",  cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $bon_sortie;

    /**
     * @ORM\ManyToOne(targetEntity=PieceRechange::class, inversedBy="ligneBS")
     * @ORM\JoinColumn(nullable=false)
     */
    private $piece;

    /**
     * @ORM\Column(type="integer")
     */
    private $quantite;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBonSortie(): ?BonSortie
    {
        return $this->bon_sortie;
    }

    public function setBonSortie(?BonSortie $bon_sortie): self
    {
        $this->bon_sortie = $bon_sortie;

        return $this;
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
}
