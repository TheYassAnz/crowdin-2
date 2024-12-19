<?php

namespace App\Controller;

use App\Exporter\CsvOpenSpoutExporter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\Routing\Attribute\Route;
use App\Form\CsvExportType;
use Symfony\Component\HttpFoundation\Request;
use App\Enum\CsvFormat;
use App\Form\CsvImportType;
use App\Importer\CsvOpenSpoutImporter;

#[Route('/csv', name: 'csv.')]
class CsvController extends AbstractController
{

    public function __construct(
        private readonly CsvOpenSpoutExporter $exporter,
        private readonly CsvOpenSpoutImporter $importer
    ) {}

    #[Route('/import', name: 'import')]
    public function import(Request $request): Response
    {
        $form = $this
            ->createForm(CsvImportType::class)
            ->handleRequest($request);

        /** @var UploadedFile $file */
        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('file')->getData();
            $data = $this->importer->import($file);

            return $this->render('csv/import.html.twig', [
                'form' => $form->createView(),
                'data' => $data ?? [],
            ]);
        }

        return $this->render('csv/import.html.twig', [
            'form' => $form->createView(),
            'data' => [],
        ]);
    }

    #[Route('/export', name: 'export')]
    public function export(Request $request): Response
    {
        $form = $this->createForm(CsvExportType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var CsvFormat $format */
            $format = $form->get('format')->getData();

            $columnNames = ['column1', 'column2', 'column3'];

            $data = [
                ['value1', 'value2', 'value3'],
                ['value4', 'value5', 'value6'],
                ['value7', 'value8', 'value9'],
            ];

            $response = new StreamedResponse(fn() => $this->exporter->export($columnNames, $data, $format));

            $response->headers->set('Content-Type', $format->contentType());

            return $response;
        }

        return $this->render('csv/export.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
