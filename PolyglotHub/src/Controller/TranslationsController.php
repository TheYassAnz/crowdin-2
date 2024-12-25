<?php

namespace App\Controller;

use App\Entity\Sources;
use App\Entity\Translations;
use App\Form\TranslationType;
use App\Repository\TranslationsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/translations')]
class TranslationsController extends AbstractController
{
    #[Route('', name: 'app_translations')]
    function index(TranslationsRepository $repository): Response
    {
        $translations  = $repository->findAll();
        return $this->render('translations/index.html.twig', [
            'controller_name' => 'TranslationsController',
            'translations' => $translations,
        ]);
    }

    #[Route('/new', name: 'app_translations_new')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $sourceId = $request->query->get('sourceId');
        $translation = new Translations();
        $source = $sourceId ? $entityManager->getRepository(Sources::class)->find($sourceId) : null;
        if ($source) {
            $translation->setSource($source);
        }
        $form = $this->createForm(TranslationType::class, $translation, ['source' => $source]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($translation);
            $entityManager->flush();
            return $this->redirectToRoute('app_sources_show', ['id' => $translation->getSource()->getId()]);
        }

        return $this->render('translations/new.html.twig', [
            'form' => $form->createView()
        ]);
    }
    
    #[Route('/{id}', name: 'app_translations_show', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function show(?Translations $translation): Response
    {
        return $this->render('translations/show.html.twig', [
            'translation' => $translation,
        ]);
    }
}
