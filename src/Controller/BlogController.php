<?php

namespace App\Controller;
use App\Entity\Client;
use App\Entity\Listedenvies;
use App\Entity\Societe;
use App\Entity\Produit;
use App\Entity\Test;
use App\Entity\Panier;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;







use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;

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

    #[Route('/formpart', name: 'formpart')]
    public function formpart(): Response
    {
        return $this->render('formpart.html.twig', [
            'message' => 'Bienvenue sur la page d\'accueil !',
        ]);
    }

    #[Route('/informations', name: 'informations')]
    public function pageinformations(): Response
    {
        return $this->render('informations.html.twig', [
            'message' => 'Bienvenue sur la page d\'accueil !',
        ]);
    }

    #[Route('/motdepasseoublie', name: 'motdepasseoublie')]
    public function motdepasseoublie(): Response
    {
        return $this->render('motdepasseoublie.html.twig', [
            'message' => 'Bienvenue sur la page d\'accueil !',
        ]);
    }

    #[Route('/test', name: 'test')]
    public function test(): Response
    {
        return $this->render('test.html.twig', [
            'message' => 'Bienvenue sur la page d\'accueil !',
        ]);
    }

    #[Route('/compte', name: 'compte')]
    public function pagecompte(): Response
    {
        return $this->render('compte.html.twig', [
            'message' => 'Bienvenue sur la page d\'accueil !',
        ]);
    }



    #[Route('/pageconnexion', name: 'pageconnexion')]
    public function Connexion(): Response
    {
        return $this->render('connexion.html.twig', [
            'message' => 'Bienvenue sur la page d\'accueil !',
        ]);
    }


    

    
    

    



    private $entityManager;
    private $panierService;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        
    }

    #[Route('/produits', name: 'produits')]
    public function index(): Response
    {
        $produits = $this->entityManager->getRepository(Produit::class)->findAll();

        return $this->render('produits.html.twig', [
            'produits' => $produits,
        ]);
    }

    
    


    


    #[Route('/comptage', name: 'comptage')]
    public function countProductsInPanierAction(EntityManagerInterface $entityManager)
    {
        // Récupérer le référentiel (repository) de l'entité Panier
        $panierRepository = $entityManager->getRepository(Panier::class);

        // Récupérer le nombre de produits dans le panier
        $count = $panierRepository->countProducts();

        // Passer la variable $count à la vue et rendre le template
        return $this->render('navbar.html.twig', [
            'count' => $count,
        ]);
    }

//
     #[Route('/image', name: 'image')]
     public function ajouterImage(Request $request): Response
     {
         if ($request->isMethod('POST')) {
             // Créer une nouvelle instance de l'entité Test
             $produit = new Produit();
 
             // Récupérer le fichier image envoyé depuis le formulaire
             $imageFile = $request->files->get('image');
 
             if ($imageFile) {
                 // Générer un nom de fichier unique
                 $nomFichier = uniqid().'.'.$imageFile->getClientOriginalExtension();
 
                 // Déplacer le fichier dans le répertoire où sont stockées les images
                 try {
                     $imageFile->move(
                         $this->getParameter('dossier_images'),
                         $nomFichier
                     );
                 } catch (FileException $e) {
                     // Gérer l'exception si le fichier ne peut pas être déplacé
                 }
 
                 // Mettre à jour la propriété 'image' de l'entité Test avec le nom du fichier
                 $produit->setImage($nomFichier);
 
                 // Persister l'entité dans la base de données
                 $this->entityManager->persist($produit);
                 $this->entityManager->flush();
 
                 // Rediriger vers une page de succès ou une autre action
                 return $this->redirectToRoute('image');
             }
         }
 
         // Afficher le formulaire
         return $this->render('image.html.twig');
     }




     #[Route('/image1', name: 'image1')]
    public function afficherImage(): Response
     {
         // Récupérer l'image depuis l'entité Test
         $test = $this->entityManager->getRepository(Test::class)->findOneBy([]);
 
         // Récupérer le nom du fichier de l'image
         $nomImage = $test ? $test->getImage() : null;
 
         // Afficher le template avec le nom du fichier de l'image
         return $this->render('image1.html.twig', [
             'nomImage' => $nomImage,
         ]);
     }
     
     

  
    #[Route('/nombre-elements-panier', name: 'nombre_elements')]
    public function nombreElementsPanier(): Response
    {
        // Obtenir le nombre d'éléments dans l'entité Panier
        $nombreElements = $this->entityManager->createQueryBuilder()
            ->select('COUNT(p.id)')
            ->from('App\Entity\Panier', 'p')
            ->getQuery()
            ->getSingleScalarResult();

        // Retourner le template Twig en passant la variable nombre_elements
        return $this->render('/navbar.html.twig', ['nombre_elements' => $nombreElements]);
    }


    //
    
    

#[Route('/affichagelistedenvie', name: 'affichagelistedenvie')]
public function affichagelistedenvie(EntityManagerInterface $entityManager): Response
{
    $listedenviesRepository = $entityManager->getRepository(Listedenvies::class);

    // Récupérer tous les éléments de la liste d'envies
    $listedenvies = $listedenviesRepository->findAll();

    return $this->render('listedenvies.html.twig', [
        'listedenvies' => $listedenvies,
    ]);
}










#[Route('/auth', name: 'votre_route')]
    public function votreAction(TokenStorageInterface $tokenStorage): Response
    {
        // Récupérer le token d'authentification
        $token = $tokenStorage->getToken();

        // Vérifier si un token d'authentification existe
        if ($token !== null) {
            // L'utilisateur est authentifié
            $user = $token->getUser();

            // Vérifier si l'utilisateur est une instance de votre entité Client
            if ($user instanceof Client) {
                // Récupérer l'ID de l'utilisateur
                $userId = $user->getId();

                // Utilisez $userId comme nécessaire

                // Afficher l'identifiant de l'utilisateur
                return new Response("L'utilisateur connecté a l'ID : $userId");
            }
        } else {
            // L'utilisateur n'est pas authentifié, redirigez-le vers la page de connexion par exemple
            return $this->redirectToRoute('connexion');
        }

        // Reste de votre logique ici
        
        return $this->render('connexionn.html.twig', [
            // Passer des données à votre vue Twig si nécessaire
        ]);
    }

    #[Route('/demanderdevis', name: 'demanderdevis')]
    public function demanderDevis(): Response
    {
        return $this->render('demanderdevis.html.twig');
    }






#[Route('/informations/{id}', name: 'recup_informations')]
public function getClientInfo($id, EntityManagerInterface $entityManager): Response
{
    // Récupérer le client spécifique par son ID
    $client = $entityManager->getRepository(Client::class)->find($id);

    // Vérifier si le client existe
    if (!$client) {
        throw $this->createNotFoundException('Client non trouvé.');
    }

    // Récupérer les informations spécifiques du client
    $nom = $client->getNom();
    $prenom = $client->getPrenom();
    $email = $client->getEmail();
    $telephone = $client->getTelephone();
    $motdepasse = $client->getMotdepasse();

    // Transmettre les informations du client au template Twig
    return $this->render('informations.html.twig', [
        'nom' => $nom,
        'prenom' => $prenom,
        'email' => $email,
        'telephone' => $telephone,
        'motdepasse' => $motdepasse,
    ]);
}


}











   
    

    

    

   

    
    