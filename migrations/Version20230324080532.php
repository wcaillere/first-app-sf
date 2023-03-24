<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230324080532 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE article DROP INDEX UNIQ_23A0E6659027487, ADD INDEX IDX_23A0E6659027487 (theme_id)');
        $this->addSql('ALTER TABLE article CHANGE theme_id theme_id INT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE article DROP INDEX IDX_23A0E6659027487, ADD UNIQUE INDEX UNIQ_23A0E6659027487 (theme_id)');
        $this->addSql('ALTER TABLE article CHANGE theme_id theme_id INT DEFAULT NULL');
    }
}
