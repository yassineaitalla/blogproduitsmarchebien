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
public function ajouterAlalistedenvie(Request $request, $id, SessionInterface  $session): Response
{
    // Récupérer le produit à partir de son identifiant
    $produit = $this->entityManager->getRepository(Produit::class)->find($id);

    // Vérifier si le produit existe
    if (!$produit) {
        // Afficher un message d'erreur
        $this->addFlash('error', 'Le produit n\'existe pas.');
        return $this->redirectToRoute('produits');
    }

    $clientId = $session->get('client_id');

    // Si l'ID du client n'est pas présent dans la session, afficher un message d'erreur
    if (!$clientId) {
        // Afficher un message d'erreur
        $this->addFlash('error', 'Identifiant client non trouvé.');
        return $this->redirectToRoute('produits');
    }

    // Récupérer le client à partir de son identifiant
    $client = $this->entityManager->getRepository(Client::class)->find($clientId);

    // Si le client n'existe pas, afficher un message d'erreur
    if (!$client) {
        // Afficher un message d'erreur
        $this->addFlash('error', 'Le client n\'existe pas.');
        return $this->redirectToRoute('produits');
    }

    // Vérifier si le produit est déjà dans la liste d'envies du client
    $existingList = $this->entityManager->getRepository(Listedenvies::class)->findOneBy(['idproduit' => $produit, 'client' => $client]);

    // Si le produit existe déjà dans la liste d'envies du client, afficher un message d'erreur
    if ($existingList) {
        $this->addFlash('error', 'Ce produit est déjà dans votre liste d\'envies.');
        return $this->redirectToRoute('produits');
    }

    // Créer une nouvelle instance de Listedenvies
    $listedenvies = new Listedenvies();
    $listedenvies->setIdproduit($produit);
    $listedenvies->setClient($client);

    // Persister la liste d'envies
    $this->entityManager->persist($listedenvies);

    // Enregistrer les modifications dans la base de données
    $this->entityManager->flush();

    // Afficher un message de succès
    $this->addFlash('success', 'Le produit a été ajouté à votre liste d\'envies.');

    // Rediriger l'utilisateur vers la page précédente
    return $this->redirectToRoute('produits');
}

}