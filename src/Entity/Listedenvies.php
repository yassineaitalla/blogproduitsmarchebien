<?php

namespace App\Entity;

use App\Repository\ListedenviesRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ListedenviesRepository::class)]
class Listedenvies
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?produit $idproduit = null;



   

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdproduit(): ?produit
    {
        return $this->idproduit;
    }

    public function setIdproduit(?produit $idproduit): static
    {
        $this->idproduit = $idproduit;

        return $this;
    }

    // Client

    private $nomListedenvies;

    public function getNomListedenvies(): ?string
    {
        return $this->nomListedenvies;
    }

    public function setNomListedenvies(string $nomListedenvies): static
    {
        $this->nomListedenvies = $nomListedenvies;

        return $this;
    }



    
}

