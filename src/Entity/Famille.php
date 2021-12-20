<?php

namespace App\Entity;

use App\Repository\FamilleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=FamilleRepository::class)
 * @UniqueEntity("libelle", message="Cette famille existe déja ")
 */
class Famille
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
    private $libelle;

    /**
     * @ORM\Column(type="integer")
     */
    private $dimention;

    /**
     * @ORM\OneToMany(targetEntity=SousFamille::class, mappedBy="fam_id")
     */
    private $sousFamilles;

    public function __construct()
    {
        $this->sousFamilles = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): self
    {
        $this->libelle = $libelle;

        return $this;
    }

    public function getDimention(): ?int
    {
        return $this->dimention;
    }

    public function setDimention(int $dimention): self
    {
        $this->dimention = $dimention;

        return $this;
    }

    /**
     * @return Collection|SousFamille[]
     */
    public function getSousFamilles(): Collection
    {
        return $this->sousFamilles;
    }

    public function addSousFamille(SousFamille $sousFamille): self
    {
        if (!$this->sousFamilles->contains($sousFamille)) {
            $this->sousFamilles[] = $sousFamille;
            $sousFamille->setFamId($this);
        }

        return $this;
    }

    public function removeSousFamille(SousFamille $sousFamille): self
    {
        if ($this->sousFamilles->contains($sousFamille)) {
            $this->sousFamilles->removeElement($sousFamille);
            // set the owning side to null (unless already changed)
            if ($sousFamille->getFamId() === $this) {
                $sousFamille->setFamId(null);
            }
        }

        return $this;
    }
}
