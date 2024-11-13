<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241113202208 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE source_language (sources_id INT NOT NULL, language_id INT NOT NULL, INDEX IDX_97FB4767DD51D0F7 (sources_id), INDEX IDX_97FB476782F1BAF4 (language_id), PRIMARY KEY(sources_id, language_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE source_language ADD CONSTRAINT FK_97FB4767DD51D0F7 FOREIGN KEY (sources_id) REFERENCES sources (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE source_language ADD CONSTRAINT FK_97FB476782F1BAF4 FOREIGN KEY (language_id) REFERENCES language (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE source_language DROP FOREIGN KEY FK_97FB4767DD51D0F7');
        $this->addSql('ALTER TABLE source_language DROP FOREIGN KEY FK_97FB476782F1BAF4');
        $this->addSql('DROP TABLE source_language');
    }
}
