<?php

namespace App\Entity;

use App\Repository\ProduitRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProduitRepository::class)]
class Produit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\Column]
    private ?float $prix = null;

    #[ORM\Column]
    private ?float $coef = null;

    #[ORM\Column]
    private ?float $Longueur = null;

    #[ORM\Column]
    private ?float $masseLineaireKgMetre = null;


    #[ORM\Column]
    private ?float $nombredecoupe = null;

    #[ORM\Column]
    private ?int $remise = null;

    #[ORM\Column(length: 255)]
    private ?string $image = null;

    #[ORM\OneToMany(targetEntity: Panier::class, mappedBy: 'id_produit', orphanRemoval: true)]
    private Collection $paniers;

    #[ORM\OneToMany(targetEntity: Stock::class, mappedBy: 'id_produit', orphanRemoval: true)]
    private Collection $stocks;

    public function __construct()
    {
        $this->paniers = new ArrayCollection();
        $this->stocks = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }


    // Description 

    
    public function getcoef(): ?float
    {
        return $this->coef;
    }

    public function setcoef(float $coef): static
    {
        $this->coef = $coef;

        return $this;
    }





    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): static
    {
        $this->image = $image;

        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): static
    {
        $this->prix = $prix;

        return $this;
    }



  

    public function getnombredecoupe(): ?float
    {
        return $this->nombredecoupe;
    }

    public function setnombredecoupe(float $massenombredecoupe): static
    {
        $this->nombredecoupe = $massenombredecoupe;

        return $this;
    }

    public function getMasseLineaireKgMetre(): ?float
    {
        return $this->masseLineaireKgMetre;
    }

    public function setMasseLineaireKgMetre(float $masseLineaireKgMetre): static
    {
        $this->masseLineaireKgMetre = $masseLineaireKgMetre;

        return $this;
    }

    ///
    

    public function getLongueur(): ?float
    {
        return $this->Longueur;
    }

    public function setLongueur(float $Longueur): static
    {
        $this->Longueur = $Longueur;

        return $this;
    } 
    

    public function getRemise(): ?int
    {
        return $this->remise;
    }

    public function setRemise(int $remise): static
    {
        $this->remise = $remise;

        return $this;
    }

    /**
     * @return Collection<int, Panier>
     */
    public function getPaniers(): Collection
    {
        return $this->paniers;
    }

    public function addPanier(Panier $panier): static
    {
        if (!$this->paniers->contains($panier)) {
            $this->paniers->add($panier);
            $panier->setIdProduit($this);
        }

        return $this;
    }

    public function removePanier(Panier $panier): static
    {
        if ($this->paniers->removeElement($panier)) {
            // set the owning side to null (unless already changed)
            if ($panier->getIdProduit() === $this) {
                $panier->setIdProduit(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Stock>
     */
    public function getStocks(): Collection
    {
        return $this->stocks;
    }

    public function addStock(Stock $stock): static
    {
        if (!$this->stocks->contains($stock)) {
            $this->stocks->add($stock);
            $stock->setIdProduit($this);
        }

        return $this;
    }

    public function removeStock(Stock $stock): static
    {
        if ($this->stocks->removeElement($stock)) {
            // set the owning side to null (unless already changed)
            if ($stock->getIdProduit() === $this) {
                $stock->setIdProduit(null);
            }
        }

        return $this;
    }

    public function getdescription(): ?string
    {
        return $this->description;
    }

    public function setdescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

}

