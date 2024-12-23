<?php

namespace App\Service;

use App\Entity\Sources;
use App\Entity\Projects;
use App\Enum\CsvFormat;
use App\Exporter\CsvOpenSpoutExporter;
use App\Importer\CsvOpenSpoutImporter;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class SourceCsvService
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly CsvOpenSpoutExporter $exporter,
        private readonly CsvOpenSpoutImporter $importer
    ) {}

    public function importSources(UploadedFile $file, Projects $project): array
    {
        $data = $this->importer->import($file);
        $sources = [];
        
        // Skip header row
        array_shift($data);
        
        foreach ($data as $row) {
            $source = new Sources();
            $source->setCle($row[0]);
            $source->setContent($row[1]);
            $source->setProject($project);
            
            $this->entityManager->persist($source);
            $sources[] = $source;
        }
        
        $this->entityManager->flush();
        return $sources;
    }

    public function exportSources(array $sources): array
    {
        $data = [];
        foreach ($sources as $source) {
            $data[] = [
                $source->getCle(),
                $source->getContent(),
                $source->getProject()->getName()
            ];
        }
        return $data;
    }
}