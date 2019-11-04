<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PrescriptionRepository")
 */
class Prescription
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="prescriptions")
     */
    private $nuser;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Doctor", inversedBy="prescriptions")
     */
    private $ndoctor;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Product")
     */
    private $idProduit;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $duration;

    public function __construct()
    {
        $this->idProduit = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNuser(): ?User
    {
        return $this->nuser;
    }

    public function setNuser(?User $nuser): self
    {
        $this->nuser = $nuser;

        return $this;
    }

    public function getNdoctor(): ?Doctor
    {
        return $this->ndoctor;
    }

    public function setNdoctor(?Doctor $ndoctor): self
    {
        $this->ndoctor = $ndoctor;

        return $this;
    }

    /**
     * @return Collection|Product[]
     */
    public function getIdProduit(): Collection
    {
        return $this->idProduit;
    }

    public function addIdProduit(Product $idProduit): self
    {
        if (!$this->idProduit->contains($idProduit)) {
            $this->idProduit[] = $idProduit;
        }

        return $this;
    }

    public function removeIdProduit(Product $idProduit): self
    {
        if ($this->idProduit->contains($idProduit)) {
            $this->idProduit->removeElement($idProduit);
        }

        return $this;
    }

    public function getDuration(): ?string
    {
        return $this->duration;
    }

    public function setDuration(string $duration): self
    {
        $this->duration = $duration;

        return $this;
    }
}
