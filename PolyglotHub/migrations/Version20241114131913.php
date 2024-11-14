<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241114131913 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE projects DROP INDEX UNIQ_5C93B3A45CBF5FE, ADD INDEX IDX_5C93B3A45CBF5FE (target_language_id)');
        $this->addSql('ALTER TABLE projects DROP INDEX UNIQ_5C93B3A4D886AA40, ADD INDEX IDX_5C93B3A4D886AA40 (start_language_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE projects DROP INDEX IDX_5C93B3A4D886AA40, ADD UNIQUE INDEX UNIQ_5C93B3A4D886AA40 (start_language_id)');
        $this->addSql('ALTER TABLE projects DROP INDEX IDX_5C93B3A45CBF5FE, ADD UNIQUE INDEX UNIQ_5C93B3A45CBF5FE (target_language_id)');
    }
}
