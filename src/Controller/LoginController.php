<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;

class LoginController extends AbstractController
{
    #[Route('/login', name: 'app_login', methods: ['GET', 'POST'])]
    public function index(AuthenticationUtils $authenticationUtils): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('login/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    #[Route('/create_account', name: 'app_create_account', methods: ['GET', 'POST'])]
    public function createAccount(
        Request $request,
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $passwordHasher
    ): Response {
        $user = new User();
        $form = $this->createForm(RegistrationType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $plainPassword = $form->get('password')->getData();
            $confirmPassword = $form->get('confmotdepasse')->getData();

            if ($plainPassword !== $confirmPassword) {
                $this->addFlash('error', 'Les mots de passe ne correspondent pas.');
                return $this->render('login/create_account.html.twig', [
                    'form' => $form->createView(),
                ]);
            }

            try {
                // Hash the password
                $hashedPassword = $passwordHasher->hashPassword($user, $plainPassword);
                $user->setPassword($hashedPassword);

                // Save the user to the database
                $entityManager->persist($user);
                $entityManager->flush();

                $this->addFlash('success', 'Compte créé avec succès ! Connectez-vous maintenant.');
                return $this->redirectToRoute('app_login');
            } catch (UniqueConstraintViolationException $e) {
                $this->addFlash('error', 'Ce pseudo ou cette adresse email est déjà utilisé.');
                return $this->render('login/create_account.html.twig', [
                    'form' => $form->createView(),
                ]);
            } catch (\Exception $e) {
                $this->addFlash('error', 'Une erreur est survenue lors de la création du compte.');
                return $this->render('login/create_account.html.twig', [
                    'form' => $form->createView(),
                ]);
            }
        }

        return $this->render('login/create_account.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    #[Route('/home', name: 'app_home', methods: ['GET'])]
    public function home(): Response
    {
        // Mock playlist data
        $playlists = [
            ['title' => 'Galactic Hits', 'image' => 'https://via.placeholder.com/200x200?text=Galactic+Hits', 'description' => 'Top tracks from the cosmos'],
            ['title' => 'Nebula Vibes', 'image' => 'https://via.placeholder.com/200x200?text=Nebula+Vibes', 'description' => 'Chill beats for stargazing'],
            ['title' => 'Starship Anthems', 'image' => 'https://via.placeholder.com/200x200?text=Starship+Anthems', 'description' => 'High-energy tracks for interstellar travel'],
            ['title' => 'Cosmic Classics', 'image' => 'https://via.placeholder.com/200x200?text=Cosmic+Classics', 'description' => 'Timeless tunes from the universe'],
        ];

        $personalized_playlists = [
            ['title' => 'Daily Mix 1', 'image' => 'https://via.placeholder.com/200x200', 'description' => 'Mariah Carey, Madonna...'],
            ['title' => 'Daily Mix 2', 'image' => 'https://via.placeholder.com/200x200', 'description' => 'Garbage, Eurythmics...'],
        ];
        $recommended_episodes = [
            ['title' => 'The Joe Rogan Experience', 'image' => 'https://via.placeholder.com/200x200', 'description' => '#2153 Dave Smith'],
            ['title' => 'Going Proyoga', 'image' => 'https://via.placeholder.com/200x200', 'description' => 'Episode 42'],
        ];
        $user_playlists = [
            ['title' => 'Yoga Vibes', 'image' => 'https://via.placeholder.com/50x50'],
            ['title' => 'StreamBeats - EDM', 'image' => 'https://via.placeholder.com/50x50'],
        ];
    
        return $this->render('home.html.twig', [
            'personalized_playlists' => $personalized_playlists,
            'recommended_episodes' => $recommended_episodes,
            'user_playlists' => $user_playlists,
        ]);
    }
    #[Route('/logout', name: 'app_logout')]
    public function logout(): void
    {
        // Handled by Symfony's security system
    }
}