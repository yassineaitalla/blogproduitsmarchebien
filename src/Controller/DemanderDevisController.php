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
        // Récupérer les données du panier et le message depuis la session ou une autre source de données
        $panierRepository = $this->entityManager->getRepository(Panier::class);
        $panier = $panierRepository->findAll(); // Remplacer par votre méthode pour récupérer le panier
        $message = $request->getSession()->get('message'); // Remplacer par la méthode pour récupérer le message
        
        // Calculer la somme totale
        $sommeTotal = 0;
        foreach ($panier as $produit) {
            $sommeTotal += $produit->getTotal(); // Supposons que vous ayez une méthode getPrixFinal() dans l'entité Panier qui calcule le prix final en fonction de la quantité, du prix de la barre, etc.
        }

        return $this->render('demanderdevis.html.twig', [
            'panier' => $panier,
            'message' => $message,
            'sommeTotal' => $sommeTotal,
        ]);
    }
}
