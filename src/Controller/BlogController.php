<?php

namespace App\Controller;
use App\Entity\Client;

use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;  // Importe la classe Route de l'attribut Route.

class BlogController extends AbstractController
{



    #[Route('/accueil', name: 'page_accueil')]
    public function accueil(): Response
    {
        return $this->render('accueil.html.twig', [
            'message' => 'Bienvenue sur la page d\'accueil !',
        ]);
    }
    #[Route('/formpro', name: 'page_formpro')]
    public function Client(Request $request, EntityManagerInterface $entityManager): Response
    {
        if ($request->isMethod('POST')) {
            // Récupérer les données du formulaire
            $nom = $request->request->get('nom');
            $prenom = $request->request->get('prenom');

            // Créer une instance de l'entité Client
            $client = new Client();
            $client->setNom($nom);
            $client->setPrenom($prenom);

            // Persister l'entité et enregistrer dans la base de données
            $entityManager->persist($client);
            $entityManager->flush();

            // Ajouter un message flash
            $this->addFlash('success', 'Le compte a été créé avec succès !');

            // Rediriger l'utilisateur vers une autre page après l'ajout
            return $this->redirectToRoute('page_formpro');  // Remplacez 'votre_route' par le nom de votre route de redirection
        }

        // Si la méthode est GET, renvoyez simplement le template
        return $this->render('formpro.html.twig');
    }
}
