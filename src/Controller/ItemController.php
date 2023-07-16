<?php

namespace App\Controller;

use App\Entity\Item;
use App\Form\ItemType;
use App\Repository\ItemRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ItemController extends AbstractController
{
    /**
     * Affiche la liste des items
     *
     * @param ItemRepository $repository
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     */
    #[Route('/item', name: 'item.index', methods: ['GET'])]
    public function index(ItemRepository $repository, PaginatorInterface $paginator, Request $request): Response
    {
        $items = $paginator->paginate(
            $repository->findAll(),
            $request->query->getInt('page', 1),
            6
        );

        return $this->render('pages/item/index.html.twig', [
            'items' => $items
        ]);
    }
/**
 * Affiche le formulaire de création d'un item
 *
 * @param Request $request
 * @param EntityManagerInterface $manager
 * @return Response
 */
    #[Route('/item/nouveau', name: 'item.new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $manager): Response
    {
        $item = new Item();
        $form = $this->createForm(ItemType::class, $item);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('image')->getData();
            $newImageName = uniqid() . '.' . $imageFile->getClientOriginalExtension();
            $imageFile->move($this->getParameter('images_directory'), $newImageName);

            $item->setImage($newImageName);

            $manager->persist($item);
            $manager->flush();

            $this->addFlash('success', 'L\'item a bien été ajouté !');

            return $this->redirectToRoute('item.index');
        }

        return $this->render('pages/item/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * Affiche le formulaire d'édition d'un item
     *
     * @param ItemRepository $repository
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @param integer $id
     * @return Response
     */
    #[Route('/item/edition/{id}', name: 'item.edit', methods: ['GET', 'POST'])]
    public function edit(ItemRepository $repository, Request $request, EntityManagerInterface $manager, int $id) : Response
    {
        $item = $repository->findOneBy(['id' => $id]);
        $form = $this->createForm(ItemType::class, $item);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('image')->getData();
            $newImageName = uniqid() . '.' . $imageFile->getClientOriginalExtension();
            $imageFile->move($this->getParameter('images_directory'), $newImageName);

            $item->setImage($newImageName);

            $manager->persist($item);
            $manager->flush();

            $this->addFlash('success', 'L\'item a bien été modifié !');

            return $this->redirectToRoute('item.index');
        }

        return $this->render('pages/item/edit.html.twig', [
            'form' => $form->createView(),
            'item' => $item
        ]);
    }

    /**
     * Supprime un item
     *
     * @param ItemRepository $repository
     * @param EntityManagerInterface $manager
     * @param integer $id
     * @return Response
     */
    #[Route('/item/suppression/{id}', name: 'item.delete', methods: ['GET'])]
    public function delete(ItemRepository $repository, EntityManagerInterface $manager, int $id) : Response
    {
        $item = $repository->findOneBy(['id' => $id]);
        $manager->remove($item);
        $manager->flush();

        $this->addFlash('success', 'L\'item a bien été supprimé !');

        return $this->redirectToRoute('item.index');
    }
}