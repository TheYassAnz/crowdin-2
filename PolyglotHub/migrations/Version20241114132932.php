<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241114132932 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE projects DROP FOREIGN KEY FK_5C93B3A45CBF5FE');
        $this->addSql('ALTER TABLE projects DROP FOREIGN KEY FK_5C93B3A4D886AA40');
        $this->addSql('DROP INDEX IDX_5C93B3A4D886AA40 ON projects');
        $this->addSql('DROP INDEX IDX_5C93B3A45CBF5FE ON projects');
        $this->addSql('ALTER TABLE projects DROP start_language_id, DROP target_language_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE projects ADD start_language_id INT NOT NULL, ADD target_language_id INT NOT NULL');
        $this->addSql('ALTER TABLE projects ADD CONSTRAINT FK_5C93B3A45CBF5FE FOREIGN KEY (target_language_id) REFERENCES language (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE projects ADD CONSTRAINT FK_5C93B3A4D886AA40 FOREIGN KEY (start_language_id) REFERENCES language (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_5C93B3A4D886AA40 ON projects (start_language_id)');
        $this->addSql('CREATE INDEX IDX_5C93B3A45CBF5FE ON projects (target_language_id)');
    }
}
