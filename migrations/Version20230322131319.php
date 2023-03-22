<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230322131319 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE book ADD author_id INT DEFAULT NULL, ADD publisher_id INT DEFAULT NULL, DROP author, DROP publisher');
        $this->addSql('ALTER TABLE book ADD CONSTRAINT FK_CBE5A331F675F31B FOREIGN KEY (author_id) REFERENCES authors (id)');
        $this->addSql('ALTER TABLE book ADD CONSTRAINT FK_CBE5A33140C86FCE FOREIGN KEY (publisher_id) REFERENCES publishers (id)');
        $this->addSql('CREATE INDEX IDX_CBE5A331F675F31B ON book (author_id)');
        $this->addSql('CREATE INDEX IDX_CBE5A33140C86FCE ON book (publisher_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE book DROP FOREIGN KEY FK_CBE5A331F675F31B');
        $this->addSql('ALTER TABLE book DROP FOREIGN KEY FK_CBE5A33140C86FCE');
        $this->addSql('DROP INDEX IDX_CBE5A331F675F31B ON book');
        $this->addSql('DROP INDEX IDX_CBE5A33140C86FCE ON book');
        $this->addSql('ALTER TABLE book ADD author VARCHAR(255) NOT NULL, ADD publisher VARCHAR(255) NOT NULL, DROP author_id, DROP publisher_id');
    }
}
