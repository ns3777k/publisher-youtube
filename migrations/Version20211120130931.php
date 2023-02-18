<?php

declare(strict_types=1);

/*
 * Made for YouTube channel https://www.youtube.com/@eazy-dev
 */

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211120130931 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE book_book_category (book_id INT NOT NULL, book_category_id INT NOT NULL, PRIMARY KEY(book_id, book_category_id))');
        $this->addSql('CREATE INDEX IDX_7A5A379416A2B381 ON book_book_category (book_id)');
        $this->addSql('CREATE INDEX IDX_7A5A379440B1D29E ON book_book_category (book_category_id)');
        $this->addSql('ALTER TABLE book_book_category ADD CONSTRAINT FK_7A5A379416A2B381 FOREIGN KEY (book_id) REFERENCES book (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE book_book_category ADD CONSTRAINT FK_7A5A379440B1D29E FOREIGN KEY (book_category_id) REFERENCES book_category (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE book ADD slug VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE book ADD image VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE book ADD authors TEXT NOT NULL');
        $this->addSql('ALTER TABLE book ADD publication_date DATE NOT NULL');
        $this->addSql('ALTER TABLE book ADD meap BOOLEAN DEFAULT \'false\' NOT NULL');
        $this->addSql('COMMENT ON COLUMN book.authors IS \'(DC2Type:simple_array)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP TABLE book_book_category');
        $this->addSql('ALTER TABLE book DROP slug');
        $this->addSql('ALTER TABLE book DROP image');
        $this->addSql('ALTER TABLE book DROP authors');
        $this->addSql('ALTER TABLE book DROP publication_date');
        $this->addSql('ALTER TABLE book DROP meap');
    }
}
