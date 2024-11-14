<?php

namespace App\Controller;

use App\Entity\Projects;
use App\Form\ProjectType;
use App\Repository\ProjectsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/projects')]
class ProjectsController extends AbstractController
{
    #[Route('', name: 'app_projects')]
    public function index(ProjectsRepository $repository): Response
    {
        $projects  = $repository->findAll();
        return $this->render('projects/index.html.twig', [
            'controller_name' => 'ProjectsController',
            'projects' => $projects,
        ]);
    }

    #[Route('/new', name: 'app_projects_new')]
    public function new(Request $request): Response
    {
        $projects = new Projects();
        $form = $this->createForm(ProjectType::class, $projects);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // faire quelque chose
        }

        return $this->render('projects/new.html.twig', [
            'form' => $form,
        ]);
    }
}
