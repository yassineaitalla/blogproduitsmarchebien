<?php

namespace App\Entity;

use App\Repository\StockRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: StockRepository::class)]
class Stock
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $quantite = null;

    #[ORM\ManyToOne(inversedBy: 'stocks')]
    #[ORM\JoinColumn(nullable: false)]
    private ?produit $id_produit = null;

    public function getId(): ?int
    {
        return $this->id;
    }


    #[ORM\ManyToOne(targetEntity: Entrepot::class, inversedBy: "stock")]
    #[ORM\JoinColumn(name: "Entrepot_id", referencedColumnName: "id")]
     
    private $Entrepot;

    public function getEntrepot(): ?Entrepot
    {
        return $this->Entrepot;
    }

    public function setEntrepot(?Entrepot $Entrepot): self
    {
        $this->Entrepot = $Entrepot;

        return $this;
    }

    #[ORM\ManyToOne(targetEntity: Fournisseur::class, inversedBy: "stock")]
    #[ORM\JoinColumn(name: "Fournisseur_id", referencedColumnName: "id")]
     
    private $Fournisseur;

    public function getFournisseur(): ?Fournisseur
    {
        return $this->Fournisseur;
    }

    public function setFournisseur(?Fournisseur $Fournisseur): self
    {
        $this->Fournisseur = $Fournisseur;

        return $this;
    }

    











    public function getQuantite(): ?int
    {
        return $this->quantite;
    }

    public function setQuantite(int $quantite): static
    {
        $this->quantite = $quantite;

        return $this;
    }

    public function getIdProduit(): ?produit
    {
        return $this->id_produit;
    }

    public function setIdProduit(?produit $id_produit): static
    {
        $this->id_produit = $id_produit;

        return $this;
    }
}
