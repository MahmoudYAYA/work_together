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
    #[Route('/', name: 'app_baie_index')]
    public function index(BaieRepository $baieRepository): Response
    {
        // Récupérer toutes les baies
        $baies = $baieRepository->findAll();

        return $this->render('baie/index.html.twig', [
            'baies' => $baies,
        ]);
    }

    #[Route('/{id}', name: 'app_baie_show', requirements: ['id' => '\d+'])]
    public function show(Baie $baie): Response
    {
        
        return $this->render('baie/show.html.twig', [
            'baie' => $baie,
        ]);
    }
}