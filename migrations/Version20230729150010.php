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
final class Version20230729150010 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE book_content_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE book_content (id INT NOT NULL, chapter_id INT NOT NULL, content TEXT NOT NULL, is_published BOOLEAN DEFAULT false NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_6DE5183F579F4768 ON book_content (chapter_id)');
        $this->addSql('ALTER TABLE book_content ADD CONSTRAINT FK_6DE5183F579F4768 FOREIGN KEY (chapter_id) REFERENCES book_chapter (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE book_content_id_seq CASCADE');
        $this->addSql('ALTER TABLE book_content DROP CONSTRAINT FK_6DE5183F579F4768');
        $this->addSql('DROP TABLE book_content');
    }
}
