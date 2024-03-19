<?php

namespace App\Entity;

use App\Repository\CommandeFournisseurRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommandeFournisseurRepository::class)]
class CommandeFournisseur
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $quantiteCommander = null;
    private ?int $quantiteRecu= null;

    #[ORM\Column(length: 255)]
    private ?string $statut = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuantiteCommander(): ?int
    {
        return $this->quantiteCommander;
    }

    public function setQuantiteCommander(int $quantiteCommander): static
    {
        $this->quantiteCommander = $quantiteCommander;

        return $this;
    }

    public function getQuantiteRecu(): ?int
    {
        return $this->quantiteRecu;
    }

    public function setQuantiteRecu(int $quantiteRecu): static
    {
        $this->quantiteRecu = $quantiteRecu;

        return $this;
    }

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(string $statut): static
    {
        $this->statut = $statut;

        return $this;
    }
}
