<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241114114557 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE project_target_languages (projects_id INT NOT NULL, language_id INT NOT NULL, INDEX IDX_29E0E23C1EDE0F55 (projects_id), INDEX IDX_29E0E23C82F1BAF4 (language_id), PRIMARY KEY(projects_id, language_id)) DEFAULT CHARACTER SET utf8');
        $this->addSql('ALTER TABLE project_target_languages ADD CONSTRAINT FK_29E0E23C1EDE0F55 FOREIGN KEY (projects_id) REFERENCES projects (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE project_target_languages ADD CONSTRAINT FK_29E0E23C82F1BAF4 FOREIGN KEY (language_id) REFERENCES language (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE project_language DROP FOREIGN KEY FK_E995FA6E1EDE0F55');
        $this->addSql('ALTER TABLE project_language DROP FOREIGN KEY FK_E995FA6E82F1BAF4');
        $this->addSql('DROP TABLE project_language');
        $this->addSql('ALTER TABLE projects ADD start_language_id INT NOT NULL, DROP start_language, DROP target_language');
        $this->addSql('ALTER TABLE projects ADD CONSTRAINT FK_5C93B3A4D886AA40 FOREIGN KEY (start_language_id) REFERENCES language (id)');
        $this->addSql('CREATE INDEX IDX_5C93B3A4D886AA40 ON projects (start_language_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE project_language (projects_id INT NOT NULL, language_id INT NOT NULL, INDEX IDX_E995FA6E1EDE0F55 (projects_id), INDEX IDX_E995FA6E82F1BAF4 (language_id), PRIMARY KEY(projects_id, language_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE project_language ADD CONSTRAINT FK_E995FA6E1EDE0F55 FOREIGN KEY (projects_id) REFERENCES projects (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE project_language ADD CONSTRAINT FK_E995FA6E82F1BAF4 FOREIGN KEY (language_id) REFERENCES language (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE project_target_languages DROP FOREIGN KEY FK_29E0E23C1EDE0F55');
        $this->addSql('ALTER TABLE project_target_languages DROP FOREIGN KEY FK_29E0E23C82F1BAF4');
        $this->addSql('DROP TABLE project_target_languages');
        $this->addSql('ALTER TABLE projects DROP FOREIGN KEY FK_5C93B3A4D886AA40');
        $this->addSql('DROP INDEX IDX_5C93B3A4D886AA40 ON projects');
        $this->addSql('ALTER TABLE projects ADD start_language VARCHAR(255) NOT NULL, ADD target_language VARCHAR(255) NOT NULL, DROP start_language_id');
    }
}
