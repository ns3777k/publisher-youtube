<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230213182824 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE book_chapter_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE book_chapter (id INT NOT NULL, book_id INT NOT NULL, parent_id INT DEFAULT NULL, slug VARCHAR(255) NOT NULL, title VARCHAR(255) NOT NULL, sort INT DEFAULT 0 NOT NULL, level INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_6AA19DB816A2B381 ON book_chapter (book_id)');
        $this->addSql('CREATE INDEX IDX_6AA19DB8727ACA70 ON book_chapter (parent_id)');
        $this->addSql('ALTER TABLE book_chapter ADD CONSTRAINT FK_6AA19DB816A2B381 FOREIGN KEY (book_id) REFERENCES book (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE book_chapter ADD CONSTRAINT FK_6AA19DB8727ACA70 FOREIGN KEY (parent_id) REFERENCES book_chapter (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE book DROP meap');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE book_chapter DROP CONSTRAINT FK_6AA19DB8727ACA70');
        $this->addSql('DROP SEQUENCE book_chapter_id_seq CASCADE');
        $this->addSql('DROP TABLE book_chapter');
        $this->addSql('ALTER TABLE book ADD meap BOOLEAN DEFAULT \'false\' NOT NULL');
    }
}
