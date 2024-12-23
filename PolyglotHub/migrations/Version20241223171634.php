<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241223171634 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE project_target_languages (projects_id INT NOT NULL, language_id INT NOT NULL, INDEX IDX_29E0E23C1EDE0F55 (projects_id), INDEX IDX_29E0E23C82F1BAF4 (language_id), PRIMARY KEY(projects_id, language_id)) DEFAULT CHARACTER SET utf8');
        $this->addSql('CREATE TABLE source_language (sources_id INT NOT NULL, language_id INT NOT NULL, INDEX IDX_97FB4767DD51D0F7 (sources_id), INDEX IDX_97FB476782F1BAF4 (language_id), PRIMARY KEY(sources_id, language_id)) DEFAULT CHARACTER SET utf8');
        $this->addSql('ALTER TABLE project_target_languages ADD CONSTRAINT FK_29E0E23C1EDE0F55 FOREIGN KEY (projects_id) REFERENCES projects (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE project_target_languages ADD CONSTRAINT FK_29E0E23C82F1BAF4 FOREIGN KEY (language_id) REFERENCES language (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE source_language ADD CONSTRAINT FK_97FB4767DD51D0F7 FOREIGN KEY (sources_id) REFERENCES sources (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE source_language ADD CONSTRAINT FK_97FB476782F1BAF4 FOREIGN KEY (language_id) REFERENCES language (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE profil CHANGE description description VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE profil ADD CONSTRAINT FK_E6D6B297C08E2885 FOREIGN KEY (preferred_language_id) REFERENCES language (id)');
        $this->addSql('ALTER TABLE profil ADD CONSTRAINT FK_E6D6B297A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('DROP INDEX IDX_5C93B3A4E70A1DC ON projects');
        $this->addSql('ALTER TABLE projects DROP target_language');
        $this->addSql('ALTER TABLE projects ADD CONSTRAINT FK_5C93B3A4A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE projects ADD CONSTRAINT FK_5C93B3A4CBF13787 FOREIGN KEY (start_language) REFERENCES language (id)');
        $this->addSql('ALTER TABLE sources ADD CONSTRAINT FK_D25D65F2166D1F9C FOREIGN KEY (project_id) REFERENCES projects (id)');
        $this->addSql('ALTER TABLE translations ADD CONSTRAINT FK_C6B7DA87953C1C61 FOREIGN KEY (source_id) REFERENCES sources (id)');
        $this->addSql('ALTER TABLE user CHANGE roles roles JSON NOT NULL, CHANGE update_date update_date DATETIME DEFAULT NULL, CHANGE verification_token verification_token VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE project_target_languages DROP FOREIGN KEY FK_29E0E23C1EDE0F55');
        $this->addSql('ALTER TABLE project_target_languages DROP FOREIGN KEY FK_29E0E23C82F1BAF4');
        $this->addSql('ALTER TABLE source_language DROP FOREIGN KEY FK_97FB4767DD51D0F7');
        $this->addSql('ALTER TABLE source_language DROP FOREIGN KEY FK_97FB476782F1BAF4');
        $this->addSql('DROP TABLE project_target_languages');
        $this->addSql('DROP TABLE source_language');
        $this->addSql('ALTER TABLE profil DROP FOREIGN KEY FK_E6D6B297C08E2885');
        $this->addSql('ALTER TABLE profil DROP FOREIGN KEY FK_E6D6B297A76ED395');
        $this->addSql('ALTER TABLE profil CHANGE description description VARCHAR(255) DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE projects DROP FOREIGN KEY FK_5C93B3A4A76ED395');
        $this->addSql('ALTER TABLE projects DROP FOREIGN KEY FK_5C93B3A4CBF13787');
        $this->addSql('ALTER TABLE projects ADD target_language INT NOT NULL');
        $this->addSql('CREATE INDEX IDX_5C93B3A4E70A1DC ON projects (target_language)');
        $this->addSql('ALTER TABLE sources DROP FOREIGN KEY FK_D25D65F2166D1F9C');
        $this->addSql('ALTER TABLE translations DROP FOREIGN KEY FK_C6B7DA87953C1C61');
        $this->addSql('ALTER TABLE user CHANGE roles roles LONGTEXT NOT NULL COLLATE `utf8mb4_bin`, CHANGE update_date update_date DATETIME NOT NULL, CHANGE verification_token verification_token VARCHAR(255) DEFAULT \'NULL\'');
    }
}
