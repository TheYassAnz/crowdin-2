<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240919082811 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }
    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE profil');
        $this->addSql('ALTER TABLE user CHANGE update_date update_date DATETIME NOT NULL');
    }
}
