<?php

namespace App\Controller;
use App\Entity\Client;





use Symfony\Component\HttpFoundation\Request;


use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;  // Importe la classe Route de l'attribut Route.

class FormulaireParticulier extends AbstractController
{


#[Route('/formpart', name: 'formpart' )]
    public function clientpart(Request $request, EntityManagerInterface $entityManager): Response
    {
        if ($request->isMethod('POST')) {
            $civilite = $request->request->get('civilite');
            $nom = $request->request->get('nom');
            $prenom = $request->request->get('prenom');
            
            $telephone = $request->request->get('telephone');
            $email = $request->request->get('email');
            $Ville = $request->request->get('Ville');
            $CodePostal = $request->request->get('CodePostal');
            $Adresse = $request->request->get('Adresse');
            $motdepasse = $request->request->get('motdepasse');
            $typeclient = $request->request->get('typeclient');

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
            $client->setTelephone($telephone);
            $client->setEmail($email);
            $client->setAdresse($Adresse);
            $client->setCodePostal($CodePostal);
            $client->setVille($Ville);
            $client->setMotdepasse($motdepasse);
            $client->settypeclient($typeclient);

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

}