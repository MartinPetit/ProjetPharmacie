<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RendezvousRepository")
 */
class Rendezvous
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $hour;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\user", inversedBy="rendezvouses")
     */
    private $nUser;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\doctor", inversedBy="rendezvouses")
     */
    private $nDoctor;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getHour(): ?\DateTimeInterface
    {
        return $this->hour;
    }

    public function setHour(\DateTimeInterface $hour): self
    {
        $this->hour = $hour;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getNUser(): ?user
    {
        return $this->nUser;
    }

    public function setNUser(?user $nUser): self
    {
        $this->nUser = $nUser;

        return $this;
    }

    public function getNDoctor(): ?doctor
    {
        return $this->nDoctor;
    }

    public function setNDoctor(?doctor $nDoctor): self
    {
        $this->nDoctor = $nDoctor;

        return $this;
    }
}
