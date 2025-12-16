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

class SecurityController extends AbstractController
{
    public function __construct(
        private UserRepository $userRepository,
        private EntityManagerInterface $em,
        private UserPasswordHasherInterface $passwordHasher
    ) {}

    /**
     * GET /login
     * Affiche la page de connexion
     */
    #[Route('/login', name: 'login', methods: ['GET', 'POST'])]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // Si l'utilisateur est déjà connecté, redirigez-le
        if ($this->getUser()) {
            return $this->redirectToRoute('user_dashboard');
        }

        // Récupérez les erreurs d'authentification
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    /**
     * GET /register
     * Affiche la page d'inscription
     */
    #[Route('/register', name: 'register', methods: ['GET', 'POST'])]
    public function register(Request $request): Response
    {
        // Si l'utilisateur est déjà connecté, redirigez-le
        if ($this->getUser()) {
            return $this->redirectToRoute('user_dashboard');
        }

        if ($request->isMethod('POST')) {
            $email = $request->request->get('email');
            $prenom = $request->request->get('prenom');
            $nom = $request->request->get('nom');
            $password = $request->request->get('password');
            $confirmPassword = $request->request->get('confirm_password');

            // Validations
            $errors = [];

            // Vérifie que l'email n'existe pas
            $existingUser = $this->userRepository->findByEmail($email);
            if ($existingUser) {
                $errors[] = 'Cet email est déjà utilisé';
            }

            // Vérifie que les mots de passe correspondent
            if ($password !== $confirmPassword) {
                $errors[] = 'Les mots de passe ne correspondent pas';
            }

            // Vérifie la longueur du mot de passe
            if (strlen($password) < 8) {
                $errors[] = 'Le mot de passe doit contenir au moins 8 caractères';
            }

            // Si pas d'erreurs, créez l'utilisateur
            if (empty($errors)) {
                $user = new User();
                $user->setEmail($email);
                $user->setPrenom($prenom);
                $user->setNom($nom);

                // Hash le mot de passe
                $hashedPassword = $this->passwordHasher->hashPassword($user, $password);
                $user->setPassword($hashedPassword);

                // Par défaut, le nouvel utilisateur a le rôle CLIENT
                $user->setRoles(['ROLE_CLIENT']);

                $this->em->persist($user);
                $this->em->flush();

                $this->addFlash('success', 'Inscription réussie ! Connectez-vous maintenant.');
                return $this->redirectToRoute('login');
            } else {
                foreach ($errors as $error) {
                    $this->addFlash('error', $error);
                }
            }
        }

        return $this->render('security/register.html.twig');
    }

    /**
     * POST /logout
     * Déconnecte l'utilisateur (géré par Symfony Security)
     */
    #[Route('/logout', name: 'logout', methods: ['POST'])]
    public function logout(): Response
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
