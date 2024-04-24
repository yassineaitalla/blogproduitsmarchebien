<?php

namespace App\Controller;

use App\Entity\Panier;
use App\Entity\Client;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface; // Importer l'EntityManager

class DemanderDevisController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/demanderdevis', name: 'demanderdevis')]
    public function demanderDevis(Request $request): Response
    {
        // Récupérer l'identifiant du client depuis la session
        $clientId = $request->getSession()->get('client_id');

        // Si l'identifiant du client n'est pas défini dans la session, rediriger vers la page de connexion
        if (!$clientId) {
            // Redirection vers la page de connexion ou affichage d'un message d'erreur
        }

        // Récupérer le panier du client à partir de son identifiant
        $panierRepository = $this->entityManager->getRepository(Panier::class);
        $panier = $panierRepository->findBy(['client' => $clientId]);

        // Calculer la somme totale
        $sommeTotal = 0;
        foreach ($panier as $produit) {
            $sommeTotal += $produit->getTotal(); // Supposons que vous ayez une méthode getTotal() dans l'entité Panier qui retourne le prix total
        }

        // Récupérer le message depuis la session ou une autre source de données
        $message = $request->getSession()->get('message'); // Remplacer par la méthode pour récupérer le message
        
        return $this->render('demanderdevis.html.twig', [
            'panier' => $panier,
            'message' => $message,
            'sommeTotal' => $sommeTotal,
        ]);
    }
}
