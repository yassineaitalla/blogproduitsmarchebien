<?php

namespace App\Controller;
use App\Entity\Client;

use App\Entity\Produit;

use App\Entity\Panier;
use Symfony\Component\HttpFoundation\Session\SessionInterface;







use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;  // Importe la classe Route de l'attribut Route.


class SupprimerProduitDupanierController extends AbstractController
{



    


#[Route('/ajouter-au-panierrrrrrr/{id}', name: 'supprimerproduitpanier')]
public function Supprimerproduitdupanier(Request $request, $id, SessionInterface $session): Response
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

    // Vérifier si le produit existe déjà dans le panier pour cet utilisateur
    $panierExistant = $this->entityManager->getRepository(Panier::class)->findOneBy(['client' => $client, 'id_produit' => $produit]);

    
    $this->entityManager->flush();

    // Supprimer l'entrée correspondante de la liste d'envies
    if ($panierExistant) {
        $this->entityManager->remove($panierExistant);
        $this->entityManager->flush();
    }

    // Rediriger l'utilisateur vers une page de confirmation ou à la page précédente
    return $this->redirectToRoute('affichagepanier');
}


    

    
    

    



    private $entityManager;
    private $panierService;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        
    }

   







}











   
    

    

    

   

    
    