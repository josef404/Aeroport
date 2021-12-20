<?php

namespace App\Entity;

use App\Repository\PiecesMachineRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PiecesMachineRepository::class)
 */
class PiecesMachine
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=PieceRechange::class, inversedBy="piecesMachines")
     * @ORM\JoinColumn(nullable=false)
     */
    private $piece;

    /**
     * @ORM\ManyToOne(targetEntity=Machine::class, inversedBy="piecesMachines")
     * @ORM\JoinColumn(nullable=false)
     */
    private $machine;

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

    public function getMachine(): ?Machine
    {
        return $this->machine;
    }

    public function setMachine(?Machine $machine): self
    {
        $this->machine = $machine;

        return $this;
    }
}
