<?php

namespace App\Controller;

use App\Entity\Client;
use App\Entity\Entrepot;
use App\Entity\Panier;
use App\Entity\Produit;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;

class AjoutaupanierController extends AbstractController
{
    private $entityManager;
    private $httpClient;

    public function __construct(EntityManagerInterface $entityManager, HttpClientInterface $httpClient)
    {
        $this->entityManager = $entityManager;
        $this->httpClient = $httpClient;
    }

    

    

#[Route("/ajouter-au-panier/{id}", name: "ajouter_au_panier")]
public function ajouterAuPanier(Request $request, $id, SessionInterface $session, EntityManagerInterface $entityManager, FlashBagInterface  $flashBag): RedirectResponse
{
    // Récupérer le produit à partir de son identifiant
    $produit = $entityManager->getRepository(Produit::class)->find($id);

    // Si le produit n'existe pas, rediriger vers une page d'erreur ou afficher un message d'erreur
    if (!$produit) {
        $flashBag->add('error', 'Produit non trouvé.');
        // Redirection vers une page d'erreur ou affichage d'un message d'erreur
    }

    // Récupérer l'ID du client à partir de la session
    $clientId = $session->get('client_id');

    // Si l'ID du client n'est pas défini, rediriger vers une page d'erreur ou afficher un message d'erreur
    if (!$clientId) {
        $flashBag->add('error', 'Client  non trouvé.');
    }

    // Récupérer le client à partir de son ID
    $client = $entityManager->getRepository(Client::class)->find($clientId);

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

    $prixInitial = $produit->getPrix();
    $total = $inp * $prixInitial * $quantite ;

    // Calculer le total en fonction de la longueur sélectionnée et du prix initial
    $masseLineaire = $produit->getMasseLineaireKgMetre();
    $coef = $produit->getCoef();
    $prixDecoupe = $masseLineaire * $coef * $inp * $quantite;
    $total = $inp * $prixInitial * $quantite + $prixDecoupe;
    $poidsKg= $masseLineaire * $inp * $quantite;

    // Calculer le poids total du panier en fonction de la masse linéaire
    $masseLineaire = $produit->getMasseLineaireKgMetre();
    $poidsKg = $masseLineaire * $inp * $quantite;

    $PrixLivraison = $masseLineaire * $inp * $quantite;

    // Récupérer l'adresse de l'entrepôt depuis la base de données
    $entrepotRepository = $entityManager->getRepository(Entrepot::class);
    $entrepot = $entrepotRepository->findOneBy([]);

    if (!$entrepot) {
        // Redirection vers une page d'erreur ou affichage d'un message d'erreur
    }

    // Récupérer les adresses
    $address1 = $entrepot->getAdresseEntrepot() . ', ' . $entrepot->getVilleEntrepot() . ', ' . $entrepot->getCodePostal();
    $address2 = $client->getAdresse() . ', ' . $client->getVille() . ', ' . $client->getCodePostal();

    // Récupérer les coordonnées géographiques des adresses
    $coordinates1 = $this->getCoordinates($address1);
    $coordinates2 = $this->getCoordinates($address2);

    if (!$coordinates1 || !$coordinates2) {
        // Redirection vers une page d'erreur ou affichage d'un message d'erreur
    }

    // Calculer la distance entre les deux points
    $distance = $this->calculateDistanceBetweenPoints($coordinates1, $coordinates2);

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
    // Définir le poids dans le panier
    $panier->setPoids($poidsKg);
    $panier->setPrixdecoupe($prixDecoupe);
    $panier->setPrixLivraison($PrixLivraison);
    // Définir la distance dans le panier
    $panier->setDistance($distance['value'] . ' ' . $distance['unit']);

    // Persister le panier
    $entityManager->persist($panier);

    // Enregistrer les modifications dans la base de données
    $entityManager->flush();

    // Redirection vers la page des produits
    return $this->redirectToRoute('produits');
}

   

    private function getCoordinates(string $address): ?array
    {
        // Appeler l'API adresse pour géocoder l'adresse et obtenir les coordonnées (latitude et longitude)
        $response = $this->httpClient->request('GET', 'https://api-adresse.data.gouv.fr/search/', [
            'query' => [
                'q' => $address,
                'limit' => 1
            ]
        ]);

        // Récupérer les données JSON de la réponse
        $data = $response->toArray();

        // Vérifier si des résultats ont été retournés
        if (!empty($data['features'])) {
            // Extraire les coordonnées (latitude et longitude) du premier résultat
            $coordinates = $data['features'][0]['geometry']['coordinates'];
            return ['latitude' => $coordinates[1], 'longitude' => $coordinates[0]];
        } else {
            return null; // En cas d'erreur ou d'absence de résultats
        }
    }

    private function calculateDistanceBetweenPoints(array $point1, array $point2): array
    {
        // Calculer la distance entre deux points en utilisant la formule Haversine
        $earthRadius = 6371; // Rayon moyen de la Terre en kilomètres
        $lat1 = deg2rad($point1['latitude']);
        $lon1 = deg2rad($point1['longitude']);
        $lat2 = deg2rad($point2['latitude']);
        $lon2 = deg2rad($point2['longitude']);

        $deltaLat = $lat2 - $lat1;
        $deltaLon = $lon2 - $lon1;

        $a = sin($deltaLat / 2) * sin($deltaLat / 2) + cos($lat1) * cos($lat2) * sin($deltaLon / 2) * sin($deltaLon / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        $distance = $earthRadius * $c;

        if ($distance < 1) {
            $distanceMeters = round($distance * 1000); // Convertir en mètres et arrondir
            return ['value' => $distanceMeters, 'unit' => 'mètres'];
        } else {
            $distanceKilometers = round($distance, 2); // Arrondir à deux décimales
            return ['value' => $distanceKilometers, 'unit' => 'kilomètres'];
        }
    }
}
