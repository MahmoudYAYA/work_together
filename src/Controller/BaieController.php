<?php

namespace App\Controller;

use App\Entity\Baie;
use App\Repository\BaieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route; 

#[Route('/baie')]
class BaieController extends AbstractController
{
    // Définire la route pour lister toutes les baies
    #[Route('/', name: 'app_baie')]
    public function index(BaieRepository $baieRepository): Response
    {
        // Récupérer toutes les baies
        $baies = $baieRepository->findAll();

        // Renvoyer les données récupérées à la vue pour al fichier tiwig 
        return $this->render('baie/index.html.twig', [
            'baies' => $baies,
        ]);
    }

    // La route pour afficher les détails d'une baie spécifique 
    #[Route('/{id}', name: 'app_baie_show', requirements: ['id' => '\d+'])]
    public function show(Baie $baie): Response
    {
        
        // Ensuite , renvoyer la baie à la vue pour affichage
        return $this->render('baie/show.html.twig', [
            'baie' => $baie,
        ]);
    }
}