<?php

namespace App\Controller;

use App\Entity\Client;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ConnexionController extends AbstractController
{
    #[Route('/connexion', name: 'connexion')]
public function seconnecter(Request $request, EntityManagerInterface $entityManager, SessionInterface $session): Response
{
    // Vérifier si la requête est de type POST
    if ($request->isMethod('POST')) {
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

        // Enregistrer l'ID du client dans la session
        $session->set('client_id', $client->getId());

        // Rediriger l'utilisateur vers la page de récupération des informations avec l'ID du client
        return $this->redirectToRoute('recup_informations', ['id' => $client->getId()]);
    }

    // Si la méthode n'est pas POST, afficher la page de connexion
    return $this->render('connexion.html.twig');
}
}
