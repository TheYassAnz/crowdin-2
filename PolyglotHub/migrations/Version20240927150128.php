<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240927150128 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE profil (id INT AUTO_INCREMENT NOT NULL, description VARCHAR(255) DEFAULT NULL, skills VARCHAR(255) NOT NULL, languages VARCHAR(255) NOT NULL, user_id INT NOT NULL, UNIQUE INDEX UNIQ_E6D6B297A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE projects (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, start_language VARCHAR(255) NOT NULL, target_language VARCHAR(255) NOT NULL, create_date DATETIME NOT NULL, update_date DATETIME NOT NULL, user_id INT NOT NULL, INDEX IDX_5C93B3A4A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE sources (id INT AUTO_INCREMENT NOT NULL, content VARCHAR(255) NOT NULL, cle VARCHAR(255) NOT NULL, project_id INT NOT NULL, create_date DATETIME NOT NULL, update_date DATETIME NOT NULL, INDEX IDX_D25D65F2166D1F9C (project_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE translations (id INT AUTO_INCREMENT NOT NULL, language VARCHAR(255) NOT NULL, translated_content VARCHAR(255) NOT NULL, create_date DATETIME NOT NULL, update_date DATETIME NOT NULL, source_id INT DEFAULT NULL, INDEX IDX_C6B7DA87953C1C61 (source_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(255) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, is_verified TINYINT(1) NOT NULL, create_date DATETIME NOT NULL, update_date DATETIME NOT NULL, verification_token VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE profil ADD CONSTRAINT FK_E6D6B297A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE projects ADD CONSTRAINT FK_5C93B3A4A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE sources ADD CONSTRAINT FK_D25D65F2166D1F9C FOREIGN KEY (project_id) REFERENCES projects (id)');
        $this->addSql('ALTER TABLE translations ADD CONSTRAINT FK_C6B7DA87953C1C61 FOREIGN KEY (source_id) REFERENCES sources (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE profil DROP FOREIGN KEY FK_E6D6B297A76ED395');
        $this->addSql('ALTER TABLE projects DROP FOREIGN KEY FK_5C93B3A4A76ED395');
        $this->addSql('ALTER TABLE sources DROP FOREIGN KEY FK_D25D65F2166D1F9C');
        $this->addSql('ALTER TABLE translations DROP FOREIGN KEY FK_C6B7DA87953C1C61');
        $this->addSql('DROP TABLE profil');
        $this->addSql('DROP TABLE projects');
        $this->addSql('DROP TABLE sources');
        $this->addSql('DROP TABLE translations');
        $this->addSql('DROP TABLE user');
    }
}
