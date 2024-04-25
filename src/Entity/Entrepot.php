<?php

namespace App\Entity;

use App\Repository\EntrepotRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EntrepotRepository::class)]
class Entrepot
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nomEntrepot = null;

    #[ORM\Column(length: 255)]
    private ?string $AdresseEntrepot = null;

    #[ORM\Column(length: 255)]
    private ?string $VilleEntrepot = null; 

    #[ORM\Column(length: 255)]
    private ?string $CodePostal = null;  

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomEntrepot(): ?string
    {
        return $this->nomEntrepot;
    }

    public function setNomEntrepot(string $nomEntrepot): static
    {
        $this->nomEntrepot = $nomEntrepot;

        return $this;
    }

    public function getAdresseEntrepot(): ?string
    {
        return $this->AdresseEntrepot;
    }

    public function setAdresseEntrepot(string $AdresseEntrepot): static
    {
        $this->AdresseEntrepot = $AdresseEntrepot;

        return $this;
    }

    public function getVilleEntrepot(): ?string
    {
        return $this->VilleEntrepot;
    }

    public function setVilleEntrepot(string $VilleEntrepot): static
    {
        $this->VilleEntrepot = $VilleEntrepot;

        return $this;
    }

    public function getCodePostal(): ?string
    {
        return $this->CodePostal;
    }

    public function setCodePostal(string $CodePostal): static
    {
        $this->CodePostal = $CodePostal;

        return $this;
    }

    
}

