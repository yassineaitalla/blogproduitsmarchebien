<?php
// nvx

namespace App\Entity;

use App\Repository\ClientRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity(repositoryClass: ClientRepository::class)]
class Client
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    private ?string $prenom = null;


    #[ORM\Column(length: 255)]
    private string $telephone ;

    public function settelephone(string $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function gettelephone(): string
    {
        return $this->telephone;
    }
//


    #[ORM\Column(length: 255)]
    private ?string $email ;

    public function setemail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getemail(): string
    {
        return $this->email;
    }

    //

    #[ORM\Column(length: 255)]
    private ?string $motdepasse ;

    public function setmotdepasse(string $motdepasse): self
    {
        $this->motdepasse = $motdepasse;

        return $this;
    }

    public function getmotdepasse(): string
    {
        return $this->motdepasse;
    }


    //


    #[ORM\OneToMany(targetEntity:Societe::class, mappedBy:"client")]
     
    private $societes;

    public function __construct()
    {
        $this->societes = new ArrayCollection();
    }

    /**
     * @return Collection|Societe[]
     */
    public function getSocietes(): Collection
    {
        return $this->societes;
    }

    public function addSociete(Societe $societe): self
    {
        if (!$this->societes->contains($societe)) {
            $this->societes[] = $societe;
            $societe->setClient($this);
        }

        return $this;
    }

    public function removeSociete(Societe $societe): self
    {
        if ($this->societes->removeElement($societe)) {
            // set the owning side to null (unless already changed)
            if ($societe->getClient() === $this) {
                $societe->setClient(null);
            }
        }

        return $this;
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

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): static
    {
        $this->prenom = $prenom;

        return $this;
    }



}
