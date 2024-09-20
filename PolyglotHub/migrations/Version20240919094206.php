<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240919094206 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE profil ADD user_id_id INT NOT NULL');
        $this->addSql('ALTER TABLE profil ADD CONSTRAINT FK_E6D6B2979D86650F FOREIGN KEY (user_id_id) REFERENCES user (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_E6D6B2979D86650F ON profil (user_id_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE profil DROP FOREIGN KEY FK_E6D6B2979D86650F');
        $this->addSql('DROP INDEX UNIQ_E6D6B2979D86650F ON profil');
        $this->addSql('ALTER TABLE profil DROP user_id_id');
    }
}
