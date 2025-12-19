<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

#[Route('/user')]
class UserController extends AbstractController
{
    public function __construct(
        private UserRepository $userRepository,
        private EntityManagerInterface $em,
        private UserPasswordHasherInterface $passwordHasher
    ) {}

    /**
     * GET /user/dashboard
     * Affiche le tableau de bord (profil) de l'utilisateur
     */
    #[Route('/dashboard', name: 'user_dashboard', methods: ['GET'])]
    public function dashboard(): Response
    {
        // Récupérez l'utilisateur authentifié et casté en User
        $user = $this->getUser();
        if (!$user instanceof User) {
            return $this->redirectToRoute('login');
        }

        return $this->render('user/dashboard.html.twig', [
            'user' => $user
        ]);
    }

    /**
     * GET /user/profile
     * Affiche la page de profil (modification des infos)
     */
    #[Route('/profile', name: 'user_profile', methods: ['GET'])]
    public function profile(): Response
    {
        $user = $this->getUser();
        if (!$user instanceof User) {
            return $this->redirectToRoute('login');
        }

        return $this->render('user/profile.html.twig', [
            'user' => $user
        ]);
    }

    /**
     * POST /user/profile
     * Traite la modification du profil
     */
    #[Route('/profile', name: 'user_profile_update', methods: ['POST'])]
    public function updateProfile(Request $request): Response
    {
        $user = $this->getUser();
        if (!$user instanceof User) {
            return $this->redirectToRoute('login');
        }

        $nom = $request->request->get('nom');
        $prenom = $request->request->get('prenom');

        if ($nom) {
            $user->setNom($nom);
        }
        if ($prenom) {
            $user->setPrenom($prenom);
        }

        $this->em->flush();

        $this->addFlash('success', 'Profil mis à jour avec succès');
        return $this->redirectToRoute('user_profile');
    }

    /**
     * GET /user/change-password
     * Affiche la page de changement de mot de passe
     */
    #[Route('/change-password', name: 'user_change_password', methods: ['GET'])]
    public function changePasswordForm(): Response
    {
        $user = $this->getUser();
        if (!$user instanceof User) {
            return $this->redirectToRoute('login');
        }

        return $this->render('user/change_password.html.twig');
    }

    /**
     * POST /user/change-password
     * Traite le changement de mot de passe
     */
    #[Route('/change-password', name: 'user_change_password_submit', methods: ['POST'])]
    public function updatePassword(Request $request): Response
    {
        $user = $this->getUser();
        if (!$user instanceof User) {
            return $this->redirectToRoute('login');
        }

        $currentPassword = $request->request->get('current_password');
        $newPassword = $request->request->get('new_password');
        $confirmPassword = $request->request->get('confirm_password');

        // Vérifiez le mot de passe actuel
        if (!$this->passwordHasher->isPasswordValid($user, $currentPassword)) {
            $this->addFlash('error', 'Le mot de passe actuel est incorrect');
            return $this->redirectToRoute('user_change_password');
        }

        // Vérifiez que les deux mots de passe correspondent
        if ($newPassword !== $confirmPassword) {
            $this->addFlash('error', 'Les mots de passe ne correspondent pas');
            return $this->redirectToRoute('user_change_password');
        }

        // Vérifiez la longueur minimale
        if (strlen($newPassword) < 8) {
            $this->addFlash('error', 'Le mot de passe doit contenir au moins 8 caractères');
            return $this->redirectToRoute('user_change_password');
        }

        // Utilisez la méthode de User pour hasher
        $user->changerMotDePasse($newPassword);
        $this->em->flush();

        $this->addFlash('success', 'Mot de passe changé avec succès');
        return $this->redirectToRoute('user_profile');
    }
}
