<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241225213721 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user ADD preferred_language_id INT NOT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649C08E2885 FOREIGN KEY (preferred_language_id) REFERENCES language (id)');
        $this->addSql('CREATE INDEX IDX_8D93D649C08E2885 ON user (preferred_language_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649C08E2885');
        $this->addSql('DROP INDEX IDX_8D93D649C08E2885 ON user');
        $this->addSql('ALTER TABLE user DROP preferred_language_id');
    }
}
