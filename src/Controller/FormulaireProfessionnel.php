<?php

namespace App\Controller;
use App\Entity\Client;
use App\Entity\Societe;

use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;  // Importe la classe Route de l'attribut Route.

class FormulaireProfessionnel extends AbstractController
{


#[Route('/formpro', name: 'page_formpro')]
    public function clientpro(Request $request, EntityManagerInterface $entityManager): Response
    {
        if ($request->isMethod('POST')) {
            $civilite = $request->request->get('civilite');
            $nom = $request->request->get('nom');
            $prenom = $request->request->get('prenom');
            $Ville = $request->request->get('Ville');
            $CodePostal = $request->request->get('CodePostal');
            $Adresse = $request->request->get('Adresse');
            $nomSociete = $request->request->get('societe');
            $siret = $request->request->get('siret');
            $telephone = $request->request->get('telephone');
            $email = $request->request->get('email');
            $motdepasse = $request->request->get('motdepasse');
            $typeclient = $request->request->get('typeclientP');

            // Vérifier si le champ "telephone" est vide
            if (empty($telephone)) {
                // Gérer le cas où le champ "telephone" est vide
                $this->addFlash('error', 'Le champ téléphone ne peut pas être vide.');
                return $this->redirectToRoute('page_formpro');
            }

            // Créer une instance de l'entité Client
            $client = new Client();
            $client->setCivilite($civilite);
            $client->setNom($nom);
            $client->setPrenom($prenom);
            $client->settypeclient($typeclient);
            $client->setAdresse($Adresse);
            $client->setCodePostal($CodePostal);
            $client->setVille($Ville);
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
            $this->addFlash('success', 'Votre compte à été crée avec succès !');

            // Rediriger l'utilisateur vers une autre page après l'ajout
            return $this->redirectToRoute('page_formpro');
        }

        // Si la méthode est GET, renvoyer simplement le template
        return $this->render('formpro.html.twig');
    }

}