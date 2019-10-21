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
     * @ORM\Column(type="string", length=255)
     */
    private $duration;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\user", inversedBy="prescriptions")
     */
    private $nuser;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\doctor", inversedBy="prescriptions")
     */
    private $ndoctor;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\product")
     */
    private $products;

    public function __construct()
    {
        $this->products = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getNuser(): ?user
    {
        return $this->nuser;
    }

    public function setNuser(?user $nuser): self
    {
        $this->nuser = $nuser;

        return $this;
    }

    public function getNdoctor(): ?doctor
    {
        return $this->ndoctor;
    }

    public function setNdoctor(?doctor $ndoctor): self
    {
        $this->ndoctor = $ndoctor;

        return $this;
    }

    /**
     * @return Collection|product[]
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(product $product): self
    {
        if (!$this->products->contains($product)) {
            $this->products[] = $product;
        }

        return $this;
    }

    public function removeProduct(product $product): self
    {
        if ($this->products->contains($product)) {
            $this->products->removeElement($product);
        }

        return $this;
    }
}
