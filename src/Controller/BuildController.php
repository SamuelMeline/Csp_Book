<?php

namespace App\Controller;

use App\Entity\Mark;
use App\Entity\Build;
use App\Form\MarkType;
use App\Form\BuildType;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query\Expr\Orx;
use App\Repository\MarkRepository;
use App\Repository\BuildRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BuildController extends AbstractController
{
    /**
     * Affiche la liste des panoplies
     *
     * @param BuildRepository $repository
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     */
    #[Route('/panoplie', name: 'build.index', methods: ['GET'])]
    #[IsGranted('ROLE_USER')]
    public function index(BuildRepository $repository, PaginatorInterface $paginator, Request $request): Response
    {
        $builds = $paginator->paginate(
            $repository->findBy(['user' => $this->getUser()]),
            $request->query->getInt('page', 1),
            6
        );

        return $this->render('pages/build/index.html.twig', [
            'builds' => $builds
        ]);
    }

    /**
     * Affiche la liste des panoplies publiques
     *
     * @param BuildRepository $repository
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     */
    #[Route('/publique', name: 'build.public', methods: ['GET'])]
    public function indexPublic(BuildRepository $repository, PaginatorInterface $paginator, Request $request): Response
    {
        $builds = $paginator->paginate(
            $repository->findPublicBuild(null),
            $request->query->getInt('page', 1),
            5
        );

        return $this->render('pages/build/public.html.twig', [
            'builds' => $builds
        ]);
    }

    /**
     * Affiche le formulaire de création d'une panoplie
     *
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    // Vérifie que l'utilisateur est connecté
    #[IsGranted('ROLE_USER')] 
    // Définit la route et les méthodes HTTP autorisées
    #[Route('/panoplie/nouveau/', name: 'build.new', methods: ['GET', 'POST'])]
    // Définit les paramètres de la méthode $request pour la requête http et $manager pour la base de données.
    //La méthode retourne une réponse.
    public function new(Request $request, EntityManagerInterface $manager): Response
    {
        // Crée une nouvelle instance de Build
        $build = new Build();
        // Crée le formulaire
        $form = $this->createForm(BuildType::class, $build);
        // Gère la requête
        $form->handleRequest($request);
        // Vérifie que le formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            // Récupère les données du formulaire
            $build = $form->getData();
            // Définit l'utilisateur comme propriétaire de la panoplie
            $build->setUser($this->getUser());

            // Enregistre la panoplie
            $manager->persist($build);
            // Enregistre les modifications
            $manager->flush();

            // Ajoute un message flash
            $this->addFlash('success', 'La panoplie a bien été créée.'); 

            // Redirige vers la liste des panoplies
            return $this->redirectToRoute('build.index'); 
        }

        // Affiche le formulaire
        return $this->render('pages/build/new.html.twig', [
            'form' => $form->createView() 
        ]);
    }

    /**
     * Affiche une panoplie
     * 
     * @param Build $build
     * @return Response
     */
    #[Security("is_granted('ROLE_USER') and build.getIsPublic() === true")]
    #[Route('/panoplie/{id}', name: 'build.show', methods: ['GET', 'POST'])]
    public function show(Build $build, Request $request, MarkRepository $markRepository, EntityManagerInterface $manager): Response
    {
        $mark = new Mark();
        $form = $this->createForm(MarkType::class, $mark);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $mark->setUser($this->getUser())
                ->setBuild($build);

            $existingMark = $markRepository->findOneBy([
                'user' => $this->getUser(),
                'build' => $build
            ]);

            if (!$existingMark) {
                $manager->persist($mark);
            } else {
                $existingMark->setMark($mark->getMark());
            }

            $manager->flush();

            $this->addFlash('success', 'La panoplie a bien été notée.');

            return $this->redirectToRoute('build.show', ['id' => $build->getId()]);
        }

        return $this->render('pages/build/show.html.twig', [
            'build' => $build,
            'form' => $form->createView()
        ]);
    }

    /**
     * Affiche le formulaire d'édition d'une panoplie
     *
     * @param Build $build
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @param integer $id
     * @return Response
     */
    #[Security("is_granted('ROLE_USER') and user === build.getUser()")]
    #[Route('/panoplie/edition/{id}', name: 'build.edit', methods: ['GET', 'POST'])]
    public function edit(Build $build, Request $request, EntityManagerInterface $manager, int $id): Response
    {
        $form = $this->createForm(BuildType::class, $build);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $build = $form->getData();

            $manager->persist($build);
            $manager->flush();

            $this->addFlash('success', 'La panoplie a bien été modifiée.');

            return $this->redirectToRoute('build.index');
        }

        return $this->render('pages/build/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * Supprime une panoplie
     *
     * @param ItemRepository $repository
     * @param EntityManagerInterface $manager
     * @param integer $id
     * @return Response
     */
    #[Route('/panoplie/suppression/{id}', name: 'build.delete', methods: ['GET'])]
    public function delete(BuildRepository $repository, EntityManagerInterface $manager, int $id): Response
    {
        $build = $repository->findOneBy(['id' => $id]);
        $manager->remove($build);
        $manager->flush();

        $this->addFlash('success', 'La panoplie a bien été supprimée.');

        return $this->redirectToRoute('build.index');
    }
}
