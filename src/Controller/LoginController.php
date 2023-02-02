<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class LoginController extends AbstractController
{
    #[Route('/login', name: 'app_login')]
    public function index(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('login/index.html.twig', [
            'last_username' => $lastUsername,
            'error'         => $error,
        ]);
    }

    #[Route('/login/redirect', name: 'app_login_redirect')]
    #[IsGranted('ROLE_USER')]
    public function redirectAfterLogin(): Response
    {
        if (in_array('ROLE_ADMIN', $this->getUser()->getRoles())) {
            return $this->redirectToRoute('app_song_index', [], Response::HTTP_SEE_OTHER);
        }
        if (in_array('ROLE_USER', $this->getUser()->getRoles())) {
            return $this->redirectToRoute('app_song_list', [], Response::HTTP_SEE_OTHER);
        }
        return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
    }
}
