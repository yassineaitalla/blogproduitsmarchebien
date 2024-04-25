<?php

namespace App\Entity;

use App\Repository\PanierRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PanierRepository::class)]
class Panier
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]

    private ?float $total = null;

    #[ORM\Column]

    private ?float $Poids = null;

    #[ORM\ManyToOne(inversedBy: 'paniers')]
    #[ORM\JoinColumn(nullable: false)]
    private ?produit $id_produit = null;

    #[ORM\Column(type: "integer")]  // Important de mettre les colonnes sinon l'ajout ne se fait pas 

    private ?int $quantite = null;

    #[ORM\Column(length: 255)]
    private ?string $Distance = null;

    //#[ORM\Column(length: 255)]
    //private ?string $PrixLivraison;

   

    public function getId(): ?int
    {
        return $this->id;
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


    public function getPoids(): ?float
    {
        return $this->Poids;
    }

    public function setPoids(float $Poids): static
    {
        $this->Poids = $Poids;

        return $this;
    }
//

    public function getquantite(): ?int
    {
        return $this->quantite;
    }

    public function setquantite(int $quantite): static
    {
        $this->quantite = $quantite;

        return $this;
    }

    public function getIdProduit(): ?Produit
    {
        return $this->id_produit;
    }

    public function setIdProduit(?Produit $id_produit): static
    {
        $this->id_produit = $id_produit;

        return $this;
    }


    // Relation vers la table clientt 

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

    // colonne longueurMetre

    #[ORM\Column(nullable: true)] // Définit la colonne comme nullable
private ?string $LongueurMetre = null;

public function getLongueurMetre(): ?string
{
    return $this->LongueurMetre;
}

public function setLongueurMetre(?string $LongueurMetre): static
{
    $this->LongueurMetre = $LongueurMetre;

    return $this;
}

#[ORM\Column(nullable: true)] // Définit la colonne comme nullable
private ?string $Prixdecoupe = null;

public function getPrixdecoupe(): ?string
{
    return $this->Prixdecoupe;
}

public function setPrixdecoupe(?string $Prixdecoupe): static
{
    $this->Prixdecoupe = $Prixdecoupe;

    return $this;
}

    public function getDistance(): ?string
    {
        return $this->Distance;
    }

    public function setDistance(string $Distance): static
    {
        $this->Distance = $Distance;

        return $this;
    }
    //
    //public function getPrixLivraison(): ?string
    //{
        //return $this->PrixLivraison;
    //}
    
    //public function setPrixLivraison(?string $PrixLivraison): static
    //{
        //$this->PrixLivraison = $PrixLivraison;
    
        //return $this;
    //}


}
