<?php

namespace App\Controller;
use App\Entity\Client;
use App\Entity\Listedenvies;
use App\Entity\Societe;
use App\Entity\Produit;
use App\Entity\Test;
use App\Entity\Panier;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
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
    public function ajouterAuPanier(Request $request, $id, SessionInterface $session, EntityManagerInterface $entityManager): Response
    {
        // Récupérer le produit à partir de son identifiant
        $produit = $entityManager->getRepository(Produit::class)->find($id);
        
        // Si le produit n'existe pas, rediriger vers une page d'erreur ou afficher un message d'erreur
        if (!$produit) {
            // Redirection vers une page d'erreur ou affichage d'un message d'erreur
        }
        
        // Récupérer l'ID du client à partir de la session
        $clientId = $session->get('client_id');
        
        // Si l'ID du client n'est pas présent dans la session, rediriger vers une page d'erreur ou afficher un message d'erreur
        if (!$clientId) {
            // Redirection vers une page d'erreur ou affichage d'un message d'erreur
        }
        
        // Récupérer le client à partir de son ID
        $client = $entityManager->getRepository(Client::class)->find($clientId);
        
        // Si le client n'existe pas, rediriger vers une page d'erreur ou afficher un message d'erreur
        if (!$client) {
            // Redirection vers une page d'erreur ou affichage d'un message d'erreur
        }
        
        // Vérifier si le produit existe déjà dans le panier du client
        $panierExistant = $entityManager->getRepository(Panier::class)->findOneBy(['client' => $client, 'id_produit' => $produit]);
        
        // Récupérer la quantité saisie par l'utilisateur
        $quantite = $request->request->get('quantite');
        
        // Vérifier si la quantité est définie et non vide
        if ($quantite !== null && $quantite !== '') {
            $quantite = intval($quantite); // Convertir en entier
        } else {
            // Si la quantité n'est pas définie ou vide, mettre la quantité par défaut à 1
            $quantite = 1;
        }
        
        // Calculer le total en multipliant la quantité par le prix du produit
        $total = $quantite * $produit->getPrix();
        
        // Vérifier s'il y a une remise sur le produit
        if ($produit->getRemise() > 0) {
            // Calculer le montant de la remise
            $remise = $total * ($produit->getRemise() / 100);
            // Appliquer la remise au total
            $total = $total - $remise;
        }
        
        // Si le produit n'est pas déjà dans le panier du client, l'ajouter
        if (!$panierExistant) {
            // Créer une nouvelle instance de Panier
            $panier = new Panier();
            // Définir le produit dans le panier
            $panier->setIdProduit($produit);
            // Définir le client dans le panier
            $panier->setClient($client);
            // Définir la quantité dans le panier
            $panier->setQuantite($quantite);
            // Définir le total dans le panier
            $panier->setTotal($total);
            // Persister le panier
            $entityManager->persist($panier);
        } else {
            // Si le produit est déjà dans le panier du client, mettre à jour la quantité et le total
            $panierExistant->setQuantite($quantite);
            $panierExistant->setTotal($total);
        }
        
        // Enregistrer les modifications dans la base de données
        $entityManager->flush();
        
        // Rediriger l'utilisateur vers une page de confirmation ou à la page précédente
        return $this->redirectToRoute('produits');
    }
}







   
    

    

    

   

    
    