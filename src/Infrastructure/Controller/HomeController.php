<?php

namespace App\Infrastructure\Controller;

use App\Infrastructure\Persistence\Repository\SongRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home', methods: ['GET'])]
    public function index(SongRepository $songRepository): Response
    {
        return $this->render('home/index.html.twig', [
            'songs' => $songRepository->randomHomeSongs(),
        ]);
    }
}
