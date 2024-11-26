<?php

namespace App\Controller\Admin;

use App\Entity\User;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'admin_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        $user = $this->getUser();

        // if ($user === null) {
        //     $this->addFlash('error', 'Utilisateur non trouvé.');
        //     return $this->redirectToRoute('home');
        // }

        // obtenir l'erreur de connexion s'il y en a une
        $error = $authenticationUtils->getLastAuthenticationError();
        // dernier nom d'utilisateur saisi
        $lastUsername = $authenticationUtils->getLastUsername();

        if ($user instanceof User && $user->isRestricted()) {
            $this->addFlash('error', 'Accès refusé.');
            return $this->redirectToRoute('home');
        }   
        
        return $this->render('admin/login.html.twig', [
            'last_username' => $lastUsername,
            'error'         => $error,
        ]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('Cette méthode peut être vide : elle sera interceptée par la clé de déconnexion de votre pare-feu.');
    }
}