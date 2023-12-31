<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Form\UserPasswordType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class UserController extends AbstractController
{
    #[Route('/search-form', name: 'search_form')]
    public function searchForm(): Response
    {
        return $this->render('pages/user/search_form.html.twig');
    }

    public function searchUsers(Request $request, UserRepository $userRepository): JsonResponse
    {
        if ($request->query->has('user')) {
            $searchTerm = $request->query->get('user');
            $users = $userRepository->findUsersBySearchTerm($searchTerm);
    
            // Créez un tableau pour stocker les résultats au format JSON
            $results = [];
            foreach ($users as $user) {
                $results[] = [
                    'id' => $user->getId(),
                    'full_name' => $user->getFullName(),
                    'pseudo' => $user->getPseudo(),
                    'email' => $user->getEmail(),
                ];
            }
    
            // Retournez les résultats au format JSON
            return new JsonResponse($results);
        }
    
        // Si aucun terme de recherche n'est fourni, retournez une réponse JSON vide
        return new JsonResponse([]);
    }

    /**
     * Permet d'afficher le profil d'un utilisateur
     *
     * @param User $choosenUser
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    #[Security("is_granted('ROLE_USER') and user === choosenUser")]
    #[Route('/utilisateur/edition/{id}', name: 'user.edit', methods: ['GET', 'POST'])]
    public function edit(User $choosenUser, Request $request, EntityManagerInterface $manager, UserPasswordHasherInterface $hasher): Response
    {
        $form = $this->createForm(UserType::class, $choosenUser);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($hasher->isPasswordValid($choosenUser, $form->getData()->getPlainPassword())) {
                $user = $form->getData();
                $manager->persist($user);
                $manager->flush();

                $this->addFlash('success', 'Votre profil a bien été modifié !');

                return $this->redirectToRoute('build.index');
            } else {
                $this->addFlash('warning', 'Le mot de passe est incorrect !');
            }
        }

        return $this->render('pages/user/edit.html.twig', [
            'controller_name' => 'UserController',
            'form' => $form->createView()
        ]);
    }

    /**
     * Permet de modifier le mot de passe d'un utilisateur
     *
     * @param User $choosenUser
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @param UserPasswordHasherInterface $hasher
     * @return Response
     */
    #[Security("is_granted('ROLE_USER') and user === choosenUser")]
    #[Route('/utilisateur/edition-mot-de-passe/{id}', 'user.edit.password', methods: ['GET', 'POST'])]
    public function editPassword(User $choosenUser, Request $request, EntityManagerInterface $manager, UserPasswordHasherInterface $hasher): Response
    {
        $form = $this->createForm(UserPasswordType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($hasher->isPasswordValid($choosenUser, $form->getData()['plainPassword'])) {
                $choosenUser->setUpdatedAt(new \DateTimeImmutable());
                $choosenUser->setPlainPassword(
                    $form->getData()['newPassword']
                );

                $manager->persist($choosenUser);
                $manager->flush();

                $this->addFlash(
                    'success',
                    'Votre mot de passe a bien été modifié !'
                );

                return $this->redirectToRoute('build.index');
            } else {
                $this->addFlash(
                    'warning',
                    'Le mot de passe est incorrect !'
                );
            }
        }

        return $this->render('pages/user/edit_password.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
