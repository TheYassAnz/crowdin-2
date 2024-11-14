<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241114111623 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE language (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, description VARCHAR(255) NOT NULL, code VARCHAR(10) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8');
        $this->addSql('CREATE TABLE profil (id INT AUTO_INCREMENT NOT NULL, description VARCHAR(255) DEFAULT NULL, skills VARCHAR(255) NOT NULL, user_id INT NOT NULL, UNIQUE INDEX UNIQ_E6D6B297A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8');
        $this->addSql('CREATE TABLE profil_language (profil_id INT NOT NULL, language_id INT NOT NULL, INDEX IDX_C8320418275ED078 (profil_id), INDEX IDX_C832041882F1BAF4 (language_id), PRIMARY KEY(profil_id, language_id)) DEFAULT CHARACTER SET utf8');
        $this->addSql('CREATE TABLE projects (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, start_language VARCHAR(255) NOT NULL, target_language VARCHAR(255) NOT NULL, create_date DATETIME NOT NULL, update_date DATETIME NOT NULL, user_id INT NOT NULL, INDEX IDX_5C93B3A4A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8');
        $this->addSql('CREATE TABLE project_language (projects_id INT NOT NULL, language_id INT NOT NULL, INDEX IDX_E995FA6E1EDE0F55 (projects_id), INDEX IDX_E995FA6E82F1BAF4 (language_id), PRIMARY KEY(projects_id, language_id)) DEFAULT CHARACTER SET utf8');
        $this->addSql('CREATE TABLE sources (id INT AUTO_INCREMENT NOT NULL, content VARCHAR(255) NOT NULL, cle VARCHAR(255) NOT NULL, project_id INT NOT NULL, create_date DATETIME NOT NULL, update_date DATETIME NOT NULL, INDEX IDX_D25D65F2166D1F9C (project_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8');
        $this->addSql('CREATE TABLE source_language (sources_id INT NOT NULL, language_id INT NOT NULL, INDEX IDX_97FB4767DD51D0F7 (sources_id), INDEX IDX_97FB476782F1BAF4 (language_id), PRIMARY KEY(sources_id, language_id)) DEFAULT CHARACTER SET utf8');
        $this->addSql('CREATE TABLE translations (id INT AUTO_INCREMENT NOT NULL, language VARCHAR(255) NOT NULL, translated_content VARCHAR(255) NOT NULL, create_date DATETIME NOT NULL, update_date DATETIME NOT NULL, source_id INT DEFAULT NULL, INDEX IDX_C6B7DA87953C1C61 (source_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8');
        $this->addSql('CREATE TABLE translation_language (translations_id INT NOT NULL, language_id INT NOT NULL, INDEX IDX_87C9EE7CACE9C349 (translations_id), INDEX IDX_87C9EE7C82F1BAF4 (language_id), PRIMARY KEY(translations_id, language_id)) DEFAULT CHARACTER SET utf8');
        $this->addSql('CREATE TABLE user_language (user_id INT NOT NULL, language_id INT NOT NULL, INDEX IDX_345695B5A76ED395 (user_id), INDEX IDX_345695B582F1BAF4 (language_id), PRIMARY KEY(user_id, language_id)) DEFAULT CHARACTER SET utf8');
        $this->addSql('ALTER TABLE profil ADD CONSTRAINT FK_E6D6B297A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE profil_language ADD CONSTRAINT FK_C8320418275ED078 FOREIGN KEY (profil_id) REFERENCES profil (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE profil_language ADD CONSTRAINT FK_C832041882F1BAF4 FOREIGN KEY (language_id) REFERENCES language (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE projects ADD CONSTRAINT FK_5C93B3A4A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE project_language ADD CONSTRAINT FK_E995FA6E1EDE0F55 FOREIGN KEY (projects_id) REFERENCES projects (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE project_language ADD CONSTRAINT FK_E995FA6E82F1BAF4 FOREIGN KEY (language_id) REFERENCES language (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sources ADD CONSTRAINT FK_D25D65F2166D1F9C FOREIGN KEY (project_id) REFERENCES projects (id)');
        $this->addSql('ALTER TABLE source_language ADD CONSTRAINT FK_97FB4767DD51D0F7 FOREIGN KEY (sources_id) REFERENCES sources (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE source_language ADD CONSTRAINT FK_97FB476782F1BAF4 FOREIGN KEY (language_id) REFERENCES language (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE translations ADD CONSTRAINT FK_C6B7DA87953C1C61 FOREIGN KEY (source_id) REFERENCES sources (id)');
        $this->addSql('ALTER TABLE translation_language ADD CONSTRAINT FK_87C9EE7CACE9C349 FOREIGN KEY (translations_id) REFERENCES translations (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE translation_language ADD CONSTRAINT FK_87C9EE7C82F1BAF4 FOREIGN KEY (language_id) REFERENCES language (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_language ADD CONSTRAINT FK_345695B5A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_language ADD CONSTRAINT FK_345695B582F1BAF4 FOREIGN KEY (language_id) REFERENCES language (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE profil DROP FOREIGN KEY FK_E6D6B297A76ED395');
        $this->addSql('ALTER TABLE profil_language DROP FOREIGN KEY FK_C8320418275ED078');
        $this->addSql('ALTER TABLE profil_language DROP FOREIGN KEY FK_C832041882F1BAF4');
        $this->addSql('ALTER TABLE projects DROP FOREIGN KEY FK_5C93B3A4A76ED395');
        $this->addSql('ALTER TABLE project_language DROP FOREIGN KEY FK_E995FA6E1EDE0F55');
        $this->addSql('ALTER TABLE project_language DROP FOREIGN KEY FK_E995FA6E82F1BAF4');
        $this->addSql('ALTER TABLE sources DROP FOREIGN KEY FK_D25D65F2166D1F9C');
        $this->addSql('ALTER TABLE source_language DROP FOREIGN KEY FK_97FB4767DD51D0F7');
        $this->addSql('ALTER TABLE source_language DROP FOREIGN KEY FK_97FB476782F1BAF4');
        $this->addSql('ALTER TABLE translations DROP FOREIGN KEY FK_C6B7DA87953C1C61');
        $this->addSql('ALTER TABLE translation_language DROP FOREIGN KEY FK_87C9EE7CACE9C349');
        $this->addSql('ALTER TABLE translation_language DROP FOREIGN KEY FK_87C9EE7C82F1BAF4');
        $this->addSql('ALTER TABLE user_language DROP FOREIGN KEY FK_345695B5A76ED395');
        $this->addSql('ALTER TABLE user_language DROP FOREIGN KEY FK_345695B582F1BAF4');
        $this->addSql('DROP TABLE language');
        $this->addSql('DROP TABLE profil');
        $this->addSql('DROP TABLE profil_language');
        $this->addSql('DROP TABLE projects');
        $this->addSql('DROP TABLE project_language');
        $this->addSql('DROP TABLE sources');
        $this->addSql('DROP TABLE source_language');
        $this->addSql('DROP TABLE translations');
        $this->addSql('DROP TABLE translation_language');
        $this->addSql('DROP TABLE user_language');
    }
}
