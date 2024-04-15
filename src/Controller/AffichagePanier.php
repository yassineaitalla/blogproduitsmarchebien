<?php

namespace App\Controller;


use App\Entity\Panier;
use App\Entity\Client;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

use Doctrine\ORM\EntityManagerInterface; // On importe l'interface EntityManagerInterface fournie par Doctrine
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;  // Importe la classe Route de l'attribut Route.

class AffichagePanier extends AbstractController{ 
    
    private $entityManager; // Pour interagir avec la base de donnes


    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        
    }

    #[Route('/affichagepanier', name: 'affichagepanier')]
public function affichagePanier(Request $request): Response
{
    // Récupérer l'ID de l'utilisateur connecté depuis la session
    $clientId = $request->getSession()->get('client_id');

    // Si l'ID du client n'est pas défini, afficher un panier vide
    if (!$clientId) {
        $panier = [];
        $sommeTotal = 0;
        $afficherDevis = false;
    } else {
        // Récupérer le client à partir de son ID
        $client = $this->entityManager->getRepository(Client::class)->find($clientId);

        // Récupérer tous les éléments du panier pour cet utilisateur
        $panierRepository = $this->entityManager->getRepository(Panier::class);
        $panier = $panierRepository->findBy(['client' => $client]);

        // Calculer la somme totale des éléments du panier
        $sommeTotal = 0;
        foreach ($panier as $produit) {
            $sommeTotal += $produit->getTotal();
        }

        // Vérifier si le client est un professionnel pour afficher le bouton de demande de devis
        $afficherDevis = $client && $client->gettypeclient() === 'ClientProfessionnel';
    }

    return $this->render('panier.html.twig', [
        'panier' => $panier,
        'sommeTotal' => $sommeTotal,
        'afficherDevis' => $afficherDevis,
    ]);
}

    
    }




