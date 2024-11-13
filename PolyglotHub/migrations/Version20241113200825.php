<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241113200825 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE profil_language (profil_id INT NOT NULL, language_id INT NOT NULL, INDEX IDX_C8320418275ED078 (profil_id), INDEX IDX_C832041882F1BAF4 (language_id), PRIMARY KEY(profil_id, language_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE project_language (projects_id INT NOT NULL, language_id INT NOT NULL, INDEX IDX_E995FA6E1EDE0F55 (projects_id), INDEX IDX_E995FA6E82F1BAF4 (language_id), PRIMARY KEY(projects_id, language_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE profil_language ADD CONSTRAINT FK_C8320418275ED078 FOREIGN KEY (profil_id) REFERENCES profil (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE profil_language ADD CONSTRAINT FK_C832041882F1BAF4 FOREIGN KEY (language_id) REFERENCES language (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE project_language ADD CONSTRAINT FK_E995FA6E1EDE0F55 FOREIGN KEY (projects_id) REFERENCES projects (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE project_language ADD CONSTRAINT FK_E995FA6E82F1BAF4 FOREIGN KEY (language_id) REFERENCES language (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE profil DROP languages');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE profil_language DROP FOREIGN KEY FK_C8320418275ED078');
        $this->addSql('ALTER TABLE profil_language DROP FOREIGN KEY FK_C832041882F1BAF4');
        $this->addSql('ALTER TABLE project_language DROP FOREIGN KEY FK_E995FA6E1EDE0F55');
        $this->addSql('ALTER TABLE project_language DROP FOREIGN KEY FK_E995FA6E82F1BAF4');
        $this->addSql('DROP TABLE profil_language');
        $this->addSql('DROP TABLE project_language');
        $this->addSql('ALTER TABLE profil ADD languages VARCHAR(255) NOT NULL');
    }
}
