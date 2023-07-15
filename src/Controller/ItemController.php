<?php

namespace App\Controller;

use App\Repository\ItemRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ItemController extends AbstractController
{
    /**
     * Display all items
     *
     * @param ItemRepository $repository
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     */
    #[Route('/items', name: 'item', methods: ['GET'])]
    public function index(ItemRepository $repository, PaginatorInterface $paginator, Request $request): Response
    {

        $items = $paginator->paginate(
            $repository->findAll(),
            $request->query->getInt('page', 1),
            8
        );

        return $this->render('pages/item/index.html.twig', [
            'items' => $items
        ]);
    }
}
