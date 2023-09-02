<?php

namespace App\Controller;

use App\Entity\Build;
use App\Form\BuildType;
use App\Repository\BuildRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
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
     * Affiche le formulaire de création d'une panoplie
     *
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    #[IsGranted('ROLE_USER')]
    #[Route('/panoplie/nouveau', name: 'build.new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $manager): Response
    {
        $build = new Build();
        $form = $this->createForm(BuildType::class, $build);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $build = $form->getData();
            $build->setUser($this->getUser());

            $manager->persist($build);
            $manager->flush();

            $this->addFlash('success', 'La panoplie a bien été créée.');

            return $this->redirectToRoute('build.index');
        }

        return $this->render('pages/build/new.html.twig', [
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
