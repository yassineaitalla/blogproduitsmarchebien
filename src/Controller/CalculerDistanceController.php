<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Client;
use App\Entity\Entrepot;
use App\Entity\Panier;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class CalculerDistanceController extends AbstractController
{
    private $entityManager;
    private $httpClient;

    public function __construct(EntityManagerInterface $entityManager, HttpClientInterface $httpClient)
    {
        $this->entityManager = $entityManager;
        $this->httpClient = $httpClient;
    }
   

    #[Route('/calculer-distance', name: 'calculer_distance')]
    public function calculerDistance(Request $request): JsonResponse
    {
        // Récupérer l'adresse de l'entrepôt depuis la base de données
        $entrepotRepository = $this->entityManager->getRepository(Entrepot::class);
        $entrepot = $entrepotRepository->findOneBy([]);
    
        if (!$entrepot) {
            return $this->json(['error' => 'Entrepot not found.']);
        }
    
        // Adresses à comparer
        $address1 = $entrepot->getAdresseEntrepot() . ', ' . $entrepot->getVilleEntrepot() . ', ' . $entrepot->getCodePostal();
    
        $clientId = $request->getSession()->get('client_id');
    
        // Récupérer l'adresse du client à partir de son ID
        $client = $this->entityManager->getRepository(Client::class)->find($clientId);
    
        if (!$client) {
            return $this->json(['error' => 'Client not found.']);
        }
    
        $address2 = $client->getAdresse() . ', ' . $client->getVille() . ', ' . $client->getCodePostal();
    
        // Récupérer les coordonnées géographiques des adresses
        $coordinates1 = $this->getCoordinates($address1);
        $coordinates2 = $this->getCoordinates($address2);
    
        if (!$coordinates1 || !$coordinates2) {
            return $this->json(['error' => 'Failed to retrieve coordinates for one or both addresses.']);
        }
    
        // Calculer la distance entre les deux points
        $distance = $this->calculateDistanceBetweenPoints($coordinates1, $coordinates2);
    
        // Sauvegarder la distance dans l'entité Panier
        $panier = new Panier();
        $panier->setDistance($distance['value']);
        
        // Persistez les modifications
        $this->entityManager->persist($panier);
        $this->entityManager->flush();
    
        // Retourner la distance en kilomètres ou en mètres sous forme de réponse JSON
        return $this->json(['distance' => $distance['value'], 'unit' => $distance['unit']]);
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
    
    
}

