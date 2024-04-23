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

    #[ORM\Column(type: "integer")]  //

    private ?int $quantite = null;

    #[ORM\Column]

    private ?float $total = null;



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

    // nomde la liste 

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

    #[ORM\ManyToOne(targetEntity: Client::class, inversedBy: "client")]
    #[ORM\JoinColumn(name: "client_id", referencedColumnName: "id")]
     
    private $client;

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): self
    {
        $this->client = $client;

        return $this;
    }

    public function getquantite(): ?int
    {
        return $this->quantite;
    }

    public function setquantite(int $quantite): static
    {
        $this->quantite = $quantite;

        return $this;
    }

    public function getTotal(): ?float
    {
        return $this->total;
    }

    public function setTotal(float $total): static
    {
        $this->total = $total;

        return $this;
    }



    
}

