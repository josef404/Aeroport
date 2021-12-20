<?php

namespace App\Entity;

use App\Repository\FournisseurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
/**
 * @ORM\Entity(repositoryClass=FournisseurRepository::class)
 * @UniqueEntity("numTel", message="Ce numero existe déja ")
 */
class Fournisseur
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
     * @ORM\Column(type="string", length=255)
     */
    private $adress;

    /**
     * @ORM\Column(type="integer")
     */
    private $numTel;

    /**
     * @ORM\ManyToOne(targetEntity=BonEntre::class, inversedBy="fournisseur")
     */
    private $bonEntre;

    /**
     * @ORM\OneToMany(targetEntity=BonEntre::class, mappedBy="fournisseur")
     */
    private $bonEntres;

    public function __construct()
    {
        $this->bonEntres = new ArrayCollection();
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

    public function getAdress(): ?string
    {
        return $this->adress;
    }

    public function setAdress(string $adress): self
    {
        $this->adress = $adress;

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

    public function getBonEntre(): ?BonEntre
    {
        return $this->bonEntre;
    }

    public function setBonEntre(?BonEntre $bonEntre): self
    {
        $this->bonEntre = $bonEntre;

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
            $bonEntre->setFournisseur($this);
        }

        return $this;
    }

    public function removeBonEntre(BonEntre $bonEntre): self
    {
        if ($this->bonEntres->contains($bonEntre)) {
            $this->bonEntres->removeElement($bonEntre);
            // set the owning side to null (unless already changed)
            if ($bonEntre->getFournisseur() === $this) {
                $bonEntre->setFournisseur(null);
            }
        }

        return $this;
    }
}
