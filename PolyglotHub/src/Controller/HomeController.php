<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController {

    #[Route('/', name: 'home')]
    function index(): Response {
        return new Response('Ceci est la HOMEPAGE');
    }
}