<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class SourcesController extends AbstractController
{
    #[Route('/sources', name: 'app_sources')]
    function index(): Response {
        return $this->render('sources/index.html.twig');
    }
}
