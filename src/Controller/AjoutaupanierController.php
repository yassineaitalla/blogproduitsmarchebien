<?php

namespace App\Controller;
Use App\Entity\Client;
Use App\Entity\Listedenvies;
Use App\Entity\Societe;
Use App\Entity\Produit;
Use App\Entity\Test;
Use App\Entity\Panier;

Use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;  // Importe la classe Route de l'attribut Route.

class AjoutaupanierController extends AbstractController
{

    private $entityManager;
    private $panierService;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        
    }

    

    #[Route("/ajouter-au-panier/{id}", name:"ajouter_au_panier")]
public function ajouterAuPanier(Request $request, $id, SessionInterface $session): Response
{
    // Récupérer le produit à partir de son identifiant
    $produit = $this->entityManager->getRepository(Produit::class)->find($id);

    // Si le produit n'existe pas, rediriger vers une page d'erreur ou afficher un message d'erreur
    if (!$produit) {
        // Redirection vers une page d'erreur ou affichage d'un message d'erreur
    }

    // Récupérer l'ID du client à partir de la session
    $clientId = $session->get('client_id');

    // Si l'ID du client n'est pas défini, rediriger vers une page d'erreur ou afficher un message d'erreur
    if (!$clientId) {
        // Redirection vers une page d'erreur ou affichage d'un message d'erreur
    }

    // Récupérer le client à partir de son ID
    $client = $this->entityManager->getRepository(Client::class)->find($clientId);

    // Si le client n'existe pas, rediriger vers une page d'erreur ou afficher un message d'erreur
    if (!$client) {
        // Redirection vers une page d'erreur ou affichage d'un message d'erreur
    }

    // Récupérer la quantité saisie par l'utilisateur
    $quantite = $request->request->get('quantite');
    $inp = $request->request->get('inp');

    // Vérifier si la quantité est définie et non vide
    if ($quantite !== null && $quantite !== '') {
        $quantite = intval($quantite); // Convertir en entier
    } else {
        // Si la quantité n'est pas définie ou vide, mettre la quantité par défaut à 1
        $quantite = 1;
    }

    // Calculer le prix total en fonction de la longueur sélectionnée
    $prixInitial = $produit->getPrix();
    $total = $inp * $prixInitial * $quantite ;

    $poidsKg = 0; // Initialiser le poids total du panier à zéro
  
        

    // Calculer le prix de découpe en fonction de la masse linéaire, du coefficient et de la longueur sélectionnée
    $masseLineaire = $produit->getMasseLineaireKgMetre();
    $coef = $produit->getCoef();
    $prixDecoupe = $masseLineaire * $coef * $inp * $quantite;
    $total = $inp * $prixInitial * $quantite + $prixDecoupe;
    $poidsKg= $masseLineaire * $inp * $quantite;

    // Créer une nouvelle instance de Panier
    $panier = new Panier();
    // Définir l'ID du produit dans le panier
    $panier->setIdProduit($produit);
    // Définir le total dans le panier
    $panier->setTotal($total);
    // Définir la quantité dans le panier
    $panier->setQuantite($quantite);
    // Définir la longueur dans le panier
    $panier->setLongueurMetre($inp);
    // Définir le client dans le panier
    $panier->setClient($client);
    $panier->setPoids($poidsKg);
    $panier->setPrixdecoupe($prixDecoupe);
    
    // Persister le panier
    $this->entityManager->persist($panier);

    // Enregistrer les modifications dans la base de données
    $this->entityManager->flush();

    // Rediriger l'utilisateur vers une page de confirmation ou à la page précédente
    return $this->redirectToRoute('produits');
}






}





   
    

    

    

   

    
    