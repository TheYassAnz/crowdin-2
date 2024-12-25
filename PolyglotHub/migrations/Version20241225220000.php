<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241225220000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE projects ADD source_language_id INT NOT NULL, ADD target_language_id INT NOT NULL');
        $this->addSql('ALTER TABLE projects ADD CONSTRAINT FK_5C93B3A4BE8EEA54 FOREIGN KEY (source_language_id) REFERENCES language (id)');
        $this->addSql('ALTER TABLE projects ADD CONSTRAINT FK_5C93B3A45CBF5FE FOREIGN KEY (target_language_id) REFERENCES language (id)');
        $this->addSql('CREATE INDEX IDX_5C93B3A4BE8EEA54 ON projects (source_language_id)');
        $this->addSql('CREATE INDEX IDX_5C93B3A45CBF5FE ON projects (target_language_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE projects DROP FOREIGN KEY FK_5C93B3A4BE8EEA54');
        $this->addSql('ALTER TABLE projects DROP FOREIGN KEY FK_5C93B3A45CBF5FE');
        $this->addSql('DROP INDEX IDX_5C93B3A4BE8EEA54 ON projects');
        $this->addSql('DROP INDEX IDX_5C93B3A45CBF5FE ON projects');
        $this->addSql('ALTER TABLE projects DROP source_language_id, DROP target_language_id');
    }
}
