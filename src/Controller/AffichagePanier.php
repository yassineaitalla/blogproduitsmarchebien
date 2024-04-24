<?php

namespace App\Controller;


use App\Entity\Panier;
use App\Entity\Client;
use App\Entity\Produit;
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
    // Initialiser la variable $panier à null
    $panier = null;
    $poidsKg = 0; // Initialiser le poids total du panier à zéro

    // Récupérer l'ID de l'utilisateur connecté depuis la session
    $clientId = $request->getSession()->get('client_id');

    // Si l'ID du client n'est pas défini, afficher un message pour indiquer qu'il n'y a pas besoin d'un tableau
    if (!$clientId) {
        $message = "Votre panier est vide. Aucun élément à afficher.";
        $sommeTotal = 0;
        $afficherDevis = false;
        $afficherPasserCommande = false;
    } else {
        // Récupérer le client à partir de son ID
        $client = $this->entityManager->getRepository(Client::class)->find($clientId);

        // Récupérer tous les éléments du panier pour cet utilisateur
        $panierRepository = $this->entityManager->getRepository(Panier::class);
        $panier = $panierRepository->findBy(['client' => $client]);

        // Calculer la somme totale des éléments du panier
        $sommeTotal = 0;
        foreach ($panier as $produitPanier) {
            // Récupérer l'entité Produit associée au panier
            $produit = $produitPanier->getIdProduit();
            
            // Vérifier si le produit existe
            if ($produit) {
                // Récupérer la masse linéaire du produit
                $masseLineaireKgMetre = $produit->getMasseLineaireKgMetre();

                // Récupérer la longueur en centimètres et la quantité du panier
                $longueurCm = $produitPanier->getLongueurCm();
                $quantite = $produitPanier->getQuantite();

                // Calculer le poids du produit et l'ajouter au poids total du panier
                $poidsKg += $masseLineaireKgMetre * ($longueurCm / 100) * $quantite; // Convertir la longueur en mètres
                $sommeTotal += $produitPanier->getTotal();
            }
        }
        
        // Vérifier si le client est un professionnel pour afficher le bouton de demande de devis
        $afficherDevis = $client && $client->gettypeclient() === 'ClientProfessionnel';

        // Si le panier de l'utilisateur est vide, afficher un message approprié
        if (empty($panier)) {
            $message = "Votre panier est vide. Aucun élément à afficher.";
            $afficherPasserCommande = false;
        } else {
            $message = null;
            $afficherPasserCommande = true;
        }
    }

    return $this->render('panier.html.twig', [
        'panier' => $panier,
        'sommeTotal' => $sommeTotal,
        'afficherDevis' => $afficherDevis,
        'message' => $message,
        'afficherPasserCommande' => $afficherPasserCommande,
        'poidsKg' => $poidsKg, // Passer le poids total du panier à la vue
    ]);
}



  

    



    
    


}

