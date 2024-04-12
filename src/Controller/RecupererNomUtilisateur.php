<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Client;

class RecupererNomUtilisateur extends AbstractController
{
    #[Route('/test5', name: 'page')] // pour acceder a la page cest /test5 et non name: 
    public function pageAvecNavbar(SessionInterface $session, EntityManagerInterface $entityManager)
    {
        // Récupérer l'ID du client à partir de la session
        $clientId = $session->get('client_id');

        // Vérifier si l'ID du client est défini dans la session
        if (!$clientId) {
            // Gérer le cas où l'ID du client n'est pas défini
            throw $this->createNotFoundException('Client ID not found in session');
        }

        // Récupérer l'entité Client correspondante à partir de l'ID
        $client = $entityManager->getRepository(Client::class)->find($clientId);

        // Vérifier si l'entité Client est trouvée
        if (!$client) {
            // Gérer le cas où l'entité Client n'est pas trouvée
            throw $this->createNotFoundException('Client not found');
        }

        // Récupérer le nom de l'utilisateur connecté
        $nomUtilisateur = $client->getNom();

        // Passez le nom de l'utilisateur connecté au template Twig et rendu du template
        return $this->render('test5.html.twig', [
            'nom_utilisateur' => $nomUtilisateur,
        ]);
    }
}
