<?php

namespace App\Controller;
use App\Entity\Client;
use App\Entity\Listedenvies;
use App\Entity\Societe;
use App\Entity\Produit;
use App\Entity\Test;
use App\Entity\Panier;

use Symfony\Component\HttpFoundation\File\Exception\FileException;



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

    
    #[Route('/formpro', name: 'page_formpro')]
    public function clientpro(Request $request, EntityManagerInterface $entityManager): Response
    {
        if ($request->isMethod('POST')) {
            $nom = $request->request->get('nom');
            $prenom = $request->request->get('prenom');
            $nomSociete = $request->request->get('societe');
            $siret = $request->request->get('siret');
            $telephone = $request->request->get('telephone');
            $email = $request->request->get('email');
            $motdepasse = $request->request->get('motdepasse');

            // Vérifier si le champ "telephone" est vide
            if (empty($telephone)) {
                // Gérer le cas où le champ "telephone" est vide
                $this->addFlash('error', 'Le champ téléphone ne peut pas être vide.');
                return $this->redirectToRoute('page_formpro');
            }

            // Créer une instance de l'entité Client
            $client = new Client();
            $client->setNom($nom);
            $client->setPrenom($prenom);
            $client->setTelephone($telephone);
            $client->setEmail($email);
            $client->setMotdepasse($motdepasse);

            // Créer une instance de l'entité Societe
            $societe = new Societe();
            $societe->setNomSociete($nomSociete);
            $societe->setSiret($siret);

            // Associer le client à la société (ajustez cela en fonction de votre relation)
            $societe->setClient($client);

            // Persister les entités et enregistrer dans la base de données
            $entityManager->persist($client);
            $entityManager->persist($societe);
            $entityManager->flush();

            // Ajouter un message flash
            $this->addFlash('success', 'Les données ont été créées avec succès !');

            // Rediriger l'utilisateur vers une autre page après l'ajout
            return $this->redirectToRoute('page_formpro');
        }

        // Si la méthode est GET, renvoyer simplement le template
        return $this->render('formpro.html.twig');
    }

    #[Route('/formpart', name: 'formpart')]
    public function clientpart(Request $request, EntityManagerInterface $entityManager): Response
    {
        if ($request->isMethod('POST')) {
            $nom = $request->request->get('nom');
            $prenom = $request->request->get('prenom');
            
            $telephone = $request->request->get('telephone');
            $email = $request->request->get('email');
            $motdepasse = $request->request->get('motdepasse');

            // Vérifier si le champ "telephone" est vide
            if (empty($telephone)) {
                // Gérer le cas où le champ "telephone" est vide
                $this->addFlash('error', 'Le champ téléphone ne peut pas être vide.');
                return $this->redirectToRoute('page_formpro');
            }

            // Créer une instance de l'entité Client
            $client = new Client();
            $client->setNom($nom);
            $client->setPrenom($prenom);
            $client->setTelephone($telephone);
            $client->setEmail($email);
            $client->setMotdepasse($motdepasse);

            // Créer une instance de l'entité Societe
            

            // Persister les entités et enregistrer dans la base de données
            $entityManager->persist($client);
        
            $entityManager->flush();

            // Ajouter un message flash
            $this->addFlash('success', 'Les données ont été créées avec succès !');

            // Rediriger l'utilisateur vers une autre page après l'ajout
            return $this->redirectToRoute('formpart');
        }

        // Si la méthode est GET, renvoyer simplement le template
        return $this->render('formpart.html.twig');
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

    #[Route("/ajouter-au-panier/{id}", name:"ajouter_au_panier")]
    public function ajouterAuPanier(Request $request, $id): Response
    {
        // Récupérer le produit à partir de son identifiant
        $produit = $this->entityManager->getRepository(Produit::class)->find($id);
       // Si le produit n'existe pas, rediriger vers une page d'erreur ou afficher un message d'erreur
        if (!$produit) {
            // Redirection vers une page d'erreur ou affichage d'un message d'erreur
        }

        
    
        // Vérifier si le produit existe déjà dans le panier
        $panierExistant = $this->entityManager->getRepository(Panier::class)->findOneBy(['id_produit' => $produit]);
    
        // Récupérer la quantité saisie par l'utilisateur
        $quantite = $request->request->get('quantite');
    
        // Vérifier si la quantité est définie et non vide
        if ($quantite !== null && $quantite !== '') {
            $quantite = intval($quantite); // Convertir en entier
        } else {
            // Si la quantité n'est pas définie ou vide, mettre la quantité par défaut à 1
            $quantite = 1;
        }
    
        // Si le produit n'est pas déjà dans le panier, l'ajouter
        if (!$panierExistant) {
            // Calculer le total en multipliant la quantité par le prix du produit
            $total = $quantite * $produit->getPrix();
    
            // Vérifier s'il y a une remise sur le produit
            if ($produit->getRemise() > 0) {
                // Calculer le montant de la remise
                $remise = $total * ($produit->getRemise() / 100);
                // Appliquer la remise au total
                $total = $total - $remise;
            }
            $quantite = $request->request->get('id');
            $client = $this->entityManager->getRepository(Client::class)->find("3");
            // Si le produit n'existe pas, rediriger vers une page d'erreur ou afficher un message d'erreur
             if (!$client) {
                 // Redirection vers une page d'erreur ou affichage d'un message d'erreur
             }
         
             // Vérifier si le produit existe déjà dans le panier
             $panierExistant = $this->entityManager->getRepository(Panier::class)->findOneBy(['client' => $client]);
    
            // Créer une nouvelle instance de Panier
            $panier = new Panier();
            // Définir l'ID du produit dans le panier
            $panier->setIdProduit($produit);
            // Définir le total dans le panier
            $panier->setTotal($total);
            // Définir la quantité dans le panier
            $panier->setQuantite($quantite);

            $panier->setClient($client);

            
       
            // Persister le panier
            $this->entityManager->persist($panier);
        } else {
            // Si le produit est déjà dans le panier, modifier la quantité
            $panierExistant->setQuantite($quantite);
            // Calculer et définir le nouveau total dans le panier
            $total = $quantite * $produit->getPrix();
    
            // Vérifier s'il y a une remise sur le produit
            if ($produit->getRemise() > 0) {
                // Calculer le montant de la remise
                $remise = $total * ($produit->getRemise() / 100);
                // Appliquer la remise au total
                $total = $total - $remise;

            }
            $panierExistant->setTotal($total);
        }

        // Enregistrer les modifications dans la base de données
        $this->entityManager->flush();
    
        // Rediriger l'utilisateur vers une page de confirmation ou à la page précédente
        return $this->redirectToRoute('produits');
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
    
    #[Route("/ajouter-au-laliste/{id}", name:"ajouter_a_la_listedenvie")]
    
    public function ajouterAlalistedenvie(Request $request, $id): Response
{
    // Récupérer le produit à partir de son identifiant
    $produit = $this->entityManager->getRepository(Produit::class)->find($id);

    // Vérifier si le produit existe
    if (!$produit) {
        // Redirection vers une page d'erreur ou affichage d'un message d'erreur
    }

    // Créer une nouvelle instance de Listedenvies
    $listedenvies = new Listedenvies();
    $listedenvies->setIdproduit($produit);

    // Persister la liste d'envies
    $this->entityManager->persist($listedenvies);

    // Enregistrer les modifications dans la base de données
    $this->entityManager->flush();

    // Rediriger l'utilisateur vers une page de confirmation ou à la page précédente
    return $this->redirectToRoute('produits');
}



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



#[Route('/connexion', name: 'connexion')]
public function seconnecter(Request $request, EntityManagerInterface $entityManager): Response
{
    // Vérifier si la requête est de type POST
    if ($request->isMethod('POST')) {
        // Récupérer les données du formulaire
        $email = $request->request->get('email');
        $motdepasse = $request->request->get('motdepasse');

        // Rechercher l'utilisateur dans la base de données par son email
        $client = $entityManager->getRepository(Client::class)->findOneBy(['email' => $email]);

        // Vérifier si l'utilisateur existe et si le mot de passe est correct
        if (!$client || $motdepasse !== $client->getMotdepasse()) {
            // Rediriger l'utilisateur vers une page d'erreur ou de connexion échouée
            $this->addFlash('error', 'Adresse e-mail ou mot de passe incorrect.');
            return $this->redirectToRoute('pageconnexion');
        }

        // Si les informations sont correctes, vous pouvez rediriger l'utilisateur vers la page de compte
        // et envoyer les informations du client à la page informations.html.twig
        return $this->redirectToRoute('recup_informations', ['id' => $client->getId()]);
    }

    // Si la méthode n'est pas POST, renvoyer une redirection vers la page de connexion
    return $this->redirectToRoute('pageconnexion');
}



#[Route('/informations', name: 'recup_informations')]
public function getClientInfo(Request $request, EntityManagerInterface $entityManager): Response
{
    // Récupérer l'ID du client depuis les paramètres de la requête
    $clientId = $request->query->get('id');

    // Vérifier si l'ID du client est fourni
    if (!$clientId) {
        // Gérer le cas où l'ID du client n'est pas fourni
        return $this->redirectToRoute('pageconnexion');
    }

    // Récupérer les informations du client depuis la base de données en utilisant son ID
    $clientRepository = $entityManager->getRepository(Client::class);
    $client = $clientRepository->find($clientId);

    // Vérifier si le client existe
    if (!$client) {
        // Gérer le cas où le client n'existe pas
        throw $this->createNotFoundException('Client non trouvé.');
    }

    // Transmettre les informations du client au template Twig
    return $this->render('informations.html.twig', [
        'client' => $client
    ]);
}



}











   
    

    

    

   

    
    


    

   




