<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241114120458 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE project_target_languages DROP FOREIGN KEY FK_29E0E23C1EDE0F55');
        $this->addSql('ALTER TABLE project_target_languages DROP FOREIGN KEY FK_29E0E23C82F1BAF4');
        $this->addSql('DROP TABLE project_target_languages');
        $this->addSql('ALTER TABLE projects DROP INDEX IDX_5C93B3A4D886AA40, ADD UNIQUE INDEX UNIQ_5C93B3A4D886AA40 (start_language_id)');
        $this->addSql('ALTER TABLE projects ADD target_language_id INT NOT NULL, DROP create_date, DROP update_date');
        $this->addSql('ALTER TABLE projects ADD CONSTRAINT FK_5C93B3A45CBF5FE FOREIGN KEY (target_language_id) REFERENCES language (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_5C93B3A45CBF5FE ON projects (target_language_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE project_target_languages (projects_id INT NOT NULL, language_id INT NOT NULL, INDEX IDX_29E0E23C82F1BAF4 (language_id), INDEX IDX_29E0E23C1EDE0F55 (projects_id), PRIMARY KEY(projects_id, language_id)) DEFAULT CHARACTER SET utf8mb3 COLLATE `utf8mb3_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE project_target_languages ADD CONSTRAINT FK_29E0E23C1EDE0F55 FOREIGN KEY (projects_id) REFERENCES projects (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE project_target_languages ADD CONSTRAINT FK_29E0E23C82F1BAF4 FOREIGN KEY (language_id) REFERENCES language (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE projects DROP INDEX UNIQ_5C93B3A4D886AA40, ADD INDEX IDX_5C93B3A4D886AA40 (start_language_id)');
        $this->addSql('ALTER TABLE projects DROP FOREIGN KEY FK_5C93B3A45CBF5FE');
        $this->addSql('DROP INDEX UNIQ_5C93B3A45CBF5FE ON projects');
        $this->addSql('ALTER TABLE projects ADD create_date DATETIME NOT NULL, ADD update_date DATETIME NOT NULL, DROP target_language_id');
    }
}
