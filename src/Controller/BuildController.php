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
    public function index(BuildRepository $repository, PaginatorInterface $paginator, Request $request): Response
    {
        $builds = $paginator->paginate(
            $repository->findAll(),
            $request->query->getInt('page', 1),
            6
        );

        return $this->render('pages/build/index.html.twig', [
            'builds' => $builds
        ]);
    }

    #[Route('/panoplie/nouveau', name: 'build.new', methods: ['GET', 'POST'])]
    public function new (Request $request, EntityManagerInterface $manager) : Response 
    {
        $build = new Build();
        $form = $this->createForm(BuildType::class, $build);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            // $imageFile = $form->get('image')->getData();
            // $newImageName = uniqid() . '.' . $imageFile->getClientOriginalExtension();
            // $imageFile->move($this->getParameter('images_directory'), $newImageName);

            // $build->setImage($newImageName);
            $build = $form->getData();

            $manager->persist($build);
            $manager->flush();

            $this->addFlash('success', 'La panoplie a bien été créée.');

            return $this->redirectToRoute('build.index');
        }

        return $this->render('pages/build/new.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
