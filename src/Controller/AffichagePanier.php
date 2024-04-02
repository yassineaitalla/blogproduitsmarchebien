<?php

namespace App\Controller;


use App\Entity\Panier;

use Doctrine\ORM\EntityManagerInterface; // On importe l'interface EntityManagerInterface fournie par Doctrine
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;  // Importe la classe Route de l'attribut Route.

class AffichagePanier extends AbstractController

{



private $entityManager; // Pour interagir avec la base de donnes


    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        
    }

    #[Route('/affichagepanier', name: 'affichagepanier')]
    public function affichagePanier(): Response

    

    
    {

        
        $panierRepository = $this->entityManager->getRepository(Panier::class);

        // Obtenir tous les éléments du panier
        $panier = $panierRepository->findAll();

        // Calculer la somme totale de la colonne total
        $query = $this->entityManager->createQuery(
            'SELECT SUM(p.total) AS sommeTotal FROM App\Entity\Panier p'
        );
        $resultat = $query->getSingleScalarResult();
        $sommeTotal = $resultat ? $resultat : 0;

        return $this->render('panier.html.twig', [
            'panier' => $panier,
            'sommeTotal' => $sommeTotal,
        ]);
    }

}