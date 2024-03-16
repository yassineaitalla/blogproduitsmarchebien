<?php

namespace App\Controller;
use App\Entity\Client;
use App\Entity\Societe;
use App\Entity\Produit;



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
            $nom = $request->request->get('nom');
            $prenom = $request->request->get('prenom');
            $nomSociete = $request->request->get('societe');
            $siret = $request->request->get('siret');
    
            // Créer une instance de l'entité Client
            $client = new Client();
            $client->setNom($nom);
            $client->setPrenom($prenom);
    
            // Créer une instance de l'entité Societe
            $societe = new Societe();
            $societe->setNomSociete($nomSociete);
            $societe->setsiret($siret);
    
            // Associer le client à la société (ajustez cela en fonction de votre relation)
            $societe->setclient($client);
    
            // Persister les entités et enregistrer dans la base de données
            $entityManager->persist($client);
            $entityManager->persist($societe);
            $entityManager->flush();
    
            // Ajouter un message flash
            $this->addFlash('success', 'Les données ont été créées avec succès !');
    
            // Rediriger l'utilisateur vers une autre page après l'ajout
            return $this->redirectToRoute('page_formpro');
        }
    
        // Si la méthode est GET, renvoyez simplement le template
        return $this->render('formpro.html.twig');
    }

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/produits', name: 'produits')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $produits = $entityManager->getRepository(Produit::class)->findAll();

        return $this->render('produits.html.twig', [
            'produits' => $produits,
        ]);
    }

    

   

    
    
}

    

   




