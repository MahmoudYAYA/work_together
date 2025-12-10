<?php

namespace App\Controller;

use App\Repository\BaieRepository;
use App\Repository\ClientRepository;
use App\Repository\UniteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(
        BaieRepository $baieRepository,
        UniteRepository $uniteRepository,
        ClientRepository $clientRepository
    ): Response {
        return $this->render('home/index.html.twig', [
            'nbBaies' => $baieRepository->count([]),
            'nbUnites' => $uniteRepository->count([]),
            'nbClients' => $clientRepository->count([]),
            'clientsActifs' => $clientRepository->count(['actif' => true]),
            'clientsInactifs' => $clientRepository->count(['actif' => false]),
        ]);
    }
}
