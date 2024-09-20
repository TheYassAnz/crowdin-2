<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240919101443 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE translations ADD source_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE translations ADD CONSTRAINT FK_C6B7DA87953C1C61 FOREIGN KEY (source_id) REFERENCES sources (id)');
        $this->addSql('CREATE INDEX IDX_C6B7DA87953C1C61 ON translations (source_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE translations DROP FOREIGN KEY FK_C6B7DA87953C1C61');
        $this->addSql('DROP INDEX IDX_C6B7DA87953C1C61 ON translations');
        $this->addSql('ALTER TABLE translations DROP source_id');
    }
}
