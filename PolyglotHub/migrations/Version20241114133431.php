<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241114133431 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE translation_language DROP FOREIGN KEY FK_87C9EE7C82F1BAF4');
        $this->addSql('ALTER TABLE translation_language DROP FOREIGN KEY FK_87C9EE7CACE9C349');
        $this->addSql('DROP TABLE translation_language');
        $this->addSql('ALTER TABLE translations DROP FOREIGN KEY FK_C6B7DA87953C1C61');
        $this->addSql('DROP INDEX IDX_C6B7DA87953C1C61 ON translations');
        $this->addSql('ALTER TABLE translations DROP source_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE translation_language (translations_id INT NOT NULL, language_id INT NOT NULL, INDEX IDX_87C9EE7C82F1BAF4 (language_id), INDEX IDX_87C9EE7CACE9C349 (translations_id), PRIMARY KEY(translations_id, language_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE translation_language ADD CONSTRAINT FK_87C9EE7C82F1BAF4 FOREIGN KEY (language_id) REFERENCES language (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE translation_language ADD CONSTRAINT FK_87C9EE7CACE9C349 FOREIGN KEY (translations_id) REFERENCES translations (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE translations ADD source_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE translations ADD CONSTRAINT FK_C6B7DA87953C1C61 FOREIGN KEY (source_id) REFERENCES sources (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_C6B7DA87953C1C61 ON translations (source_id)');
    }
}
