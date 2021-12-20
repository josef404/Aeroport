<?php

namespace App\Entity;

use App\Repository\DemontageRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DemontageRepository::class)
 */
class Demontage
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;




    /**
     * @ORM\Column(type="text")
     */
    private $piece_rechange;

    /**
     * @ORM\ManyToOne(targetEntity=Machine::class, inversedBy="demontages")
     * @ORM\JoinColumn(nullable=false)
     */
    private $machine;



    public function getId(): ?int
    {
        return $this->id;
    }





    public function getPieceRechange(): ?string
    {
        return $this->piece_rechange;
    }

    public function setPieceRechange(string $piece_rechange): self
    {
        $this->piece_rechange = $piece_rechange;

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
