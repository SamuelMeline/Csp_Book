<?php

namespace App\Controller;

use App\Entity\Item;
use App\Form\ItemType;
use App\Repository\ItemRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
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
    #[IsGranted('ROLE_USER')]
    public function index(ItemRepository $repository, PaginatorInterface $paginator, Request $request): Response
    {
        $items = $paginator->paginate(
            $repository->findBy(['user' => $this->getUser()]),
            $request->query->getInt('page', 1),
            50
        );

        return $this->render('pages/item/index.html.twig', [
            'items' => $items
        ]);
    }

    /**
     * Rechercher des items
     *
     * @param Request $request
     * @param BuildRepository $buildRepository
     * @return JsonResponse
     */
    #[Route('/search-items', name: 'search.items')]
    public function searchBuilds(Request $request, ItemRepository $itemRepository): JsonResponse
    {
        if ($request->query->has('item')) {
            $searchTerm = $request->query->get('item');
            $items = $itemRepository->findItemsBySearchTerm($searchTerm);

            // Créez un tableau pour stocker les résultats au format JSON
            $results = [];
            foreach ($items as $item) {
                
                $results[] = [
                    'image' => $item->getImage(),
                    'name' => $item->getName(),
                    'price' => $item->getPrice(),
                    'date de création' => $item->getCreatedAt()->format('d/m/Y'),
                    // Ajoutez d'autres données de panoplie ici selon vos besoins
                ];
            }

            // Retournez les résultats au format JSON
            return new JsonResponse($results);
        }

        // Si aucun terme de recherche n'est fourni, retournez une réponse JSON vide
        return new JsonResponse([]);
    }

    
    /**
     * Affiche le formulaire de création d'un item
     *
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    #[Route('/item/nouveau', name: 'item.new', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_USER')]
    public function new(Request $request, EntityManagerInterface $manager): Response
    {
        $item = new Item();
        $form = $this->createForm(ItemType::class, $item);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('image')->getData();
            $imageDirectory = $this->getParameter('images_directory') . '/items';
            $newImageName = uniqid() . '.' . $imageFile->getClientOriginalExtension();
            $imageFile->move($imageDirectory, $newImageName);

            $item->setImage($newImageName);
            $item->setUser($this->getUser());

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
     * @param Item $item
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @param integer $id
     * @return Response
     */
    #[Security("is_granted('ROLE_USER') and user === item.getUser()")]
    #[Route('/item/edition/{id}', name: 'item.edit', methods: ['GET', 'POST'])]
    public function edit(Item $item, Request $request, EntityManagerInterface $manager, int $id): Response
    {
        $form = $this->createForm(ItemType::class, $item, [
            'is_edit' => false
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('image')->getData();
            $imageDirectory = $this->getParameter('images_directory') . '/items';
            $newImageName = uniqid() . '.' . $imageFile->getClientOriginalExtension();
            $imageFile->move($imageDirectory, $newImageName);

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
    public function delete(ItemRepository $repository, EntityManagerInterface $manager, int $id): Response
    {
        $item = $repository->findOneBy(['id' => $id]);
        $manager->remove($item);
        $manager->flush();

        // Supprimer l'item du dossier public/images/items
        $imageDirectory = $this->getParameter('images_directory') . '/items';
        unlink($imageDirectory . '/' . $item->getImage());

        $this->addFlash('success', 'L\'item a bien été supprimé !');

        return $this->redirectToRoute('item.index');
    }
}
