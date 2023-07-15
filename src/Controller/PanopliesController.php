<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PanopliesController extends AbstractController
{
    #[Route('/panoplies', 'panoplies.index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('pages/panoplies.html.twig');
    }
}
