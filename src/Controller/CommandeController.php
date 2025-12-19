<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Repository\CommandeRepository;
use Doctrine\Migrations\Tools\Console\Command\UpToDateCommand;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\DependencyInjection\Loader\Configurator;

#[Route('/commande')]
final class CommandeController extends AbstractController
{
    //Liste des commandes
    #[Route('', name: 'app_commande', methods: ['GET'])]
    public function index(CommandeRepository $commandeRepository): Response
    {
        return $this->render('commande/index.html.twig', [
            'commandes' => $commandeRepository->findAll(),
        ]);
    }

    //Créer une nouvelle commande
    #[Route('/new', name: 'commande_new', methods: ['GET', 'POST'])]
    public function new(
        Request $request,
        EntityManagerInterface $entityManager
    ): Response {
        $commande = new Commande();

        // à faire les formulaires à plus trard 

        if ($request->isMethod('POST')) {
            $commande->setNumeroCommande('CMD-' . uniqid());
            $commande->setStatut('En attente paiement');
            $commande->setDateCommande(new \DateTime());

            $entityManager->persist($commande);
            $entityManager->flush();

            // Redirection vers la liste après création
            return $this->redirectToRoute('commande_index');
        }

        return $this->render('commande/new.html.twig', [
            'commande' => $commande,
        ]);
    }

    // Voir le détail d’une commande
    #[Route('/{id}', name: 'commande_show', methods: ['GET'])]
    public function show(Commande $commande): Response
    {
        return $this->render('commande/show.html.twig', [
            'commande' => $commande,
        ]);
    }

    //Payer une commande
    #[Route('/{id}/payer', name: 'commande_paied', methods: ['GET'])]
    public function payer(
        Commande $commande,
        EntityManagerInterface $entityManager
    ): Response {
        // On simule un paiement réussi
        $commande->setStatut('Payée');

        $entityManager->flush();

        return $this->redirectToRoute('commande_index');
    }

    //Supprimer / annuler une commande
    #[Route('/{id}', name: 'commande_delete', methods: ['DELETE'])]
    public function delete(
        Commande $commande,
        EntityManagerInterface $entityManager
    ): Response {
        $entityManager->remove($commande);
        $entityManager->flush();

        return $this->redirectToRoute('commande_index');
    }
}
