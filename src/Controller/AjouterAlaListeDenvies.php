<?php

namespace App\Controller;

use App\Entity\Client;
use App\Entity\Produit;
use App\Entity\Listedenvies;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;  // Importe la classe Route de l'attribut Route.


use Symfony\Component\HttpFoundation\Session\SessionInterface;




  // Importe la classe Route de l'attribut Route.

class AjouterAlaListeDenvies extends AbstractController

{
    private $entityManager;
    private $panierService;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        
    }

    #[Route("/ajouter-au-laliste/{id}", name:"ajouter_a_la_listedenvie")]
    
    public function ajouterAlalistedenvie(Request $request, $id , SessionInterface $session,): Response
{
    // Récupérer le produit à partir de son identifiant
    $produit = $this->entityManager->getRepository(Produit::class)->find($id);

    // Vérifier si le produit existe
    if (!$produit) {
        // Redirection vers une page d'erreur ou affichage d'un message d'erreur
    }

    $clientId= $session->get('client_id');
    
    // Si l'ID du client n'est pas présent dans la session, rediriger vers une page d'erreur ou afficher un message d'erreur
    if(!$clientId) {
        // Redirection vers une page d'erreur ou affichage d'un message d'erreur
    }

            $quantite = $request->request->get('quantite');
            $client = $this->entityManager->getRepository(Client::class)->find($clientId);
            // Si le produit n'existe pas, rediriger vers une page d'erreur ou afficher un message d'erreur
             if (!$client) {
                 // Redirection vers une page d'erreur ou affichage d'un message d'erreur
             }

    // Créer une nouvelle instance de Listedenvies
    $listedenvies = new Listedenvies();
    $listedenvies->setIdproduit($produit);
    $listedenvies->setClient($client);

    // Persister la liste d'envies
    $this->entityManager->persist($listedenvies);

    // Enregistrer les modifications dans la base de données
    $this->entityManager->flush();

    // Rediriger l'utilisateur vers une page de confirmation ou à la page précédente
    return $this->redirectToRoute('produits');
}
}