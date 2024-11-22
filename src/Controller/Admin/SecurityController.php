<?php

namespace App\Controller\Admin;

use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'admin_login')]
    public function login(AuthenticationUtils $authenticationUtils, AuthorizationCheckerInterface $authorizationChecker): Response
    {
        // obtenir l'erreur de connexion s'il y en a une
        $error = $authenticationUtils->getLastAuthenticationError();
        // dernier nom d'utilisateur saisi
        $lastUsername = $authenticationUtils->getLastUsername();

        $user = $this->getUser();

        // if ($user === null) {
        //     $this->addFlash('error', 'Utilisateur non trouvé.');
        //     return $this->redirectToRoute('home');
        // }

        dd($user);
        if ($user instanceof User && $user->isRestricted() === true) {
        // if ($user instanceof User && !in_array('ROLE_USER_RESTRICTED', $user->getRoles())) {
        // if ($authorizationChecker->isGranted('ROLE_USER_RESTRICTED') && !$this->getUser()) {
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