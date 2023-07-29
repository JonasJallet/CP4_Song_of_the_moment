<?php

namespace App\Infrastructure\Controller;

use App\Infrastructure\Persistence\Repository\SongRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin')]
class AdminController extends AbstractController
{
    #[Route('/', name: 'admin_index', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function index(Request $request, SongRepository $songRepository): Response
    {
        if ($request->isMethod('POST') && !empty($request->get('title'))) {
            $title = $request->get('title');
            $songs = $songRepository->findLikeTitle($title);
        } elseif ($request->isMethod('POST') && !empty($request->get('isApproved'))) {
            $isApproved = $request->get('isApproved');
            $songs = $songRepository->findLikeApproved($isApproved);
        } else {
            $songs = $songRepository->findBy([], ['isApproved' => 'asc', 'id' => 'asc']);
        }
        return $this->render('admin/index.html.twig', [
            'songs' => $songs,
        ]);
    }
}
