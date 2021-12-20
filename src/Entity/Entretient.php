<?php

namespace App\Entity;

use App\Repository\EntretientRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EntretientRepository::class)
 */
class Entretient
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Machine::class, inversedBy="entretients")
     * @ORM\JoinColumn(nullable=false)
     */
    private $machine;

    /**
     * @ORM\Column(type="date")
     */
    private $date_prochain_entretient;

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

    public function getDateProchainEntretient(): ?\DateTimeInterface
    {
        return $this->date_prochain_entretient;
    }

    public function setDateProchainEntretient(\DateTimeInterface $date_prochain_entretient): self
    {
        $this->date_prochain_entretient = $date_prochain_entretient;

        return $this;
    }
}
