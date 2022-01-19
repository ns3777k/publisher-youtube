<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220115213137 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE book DROP CONSTRAINT fk_cbe5a33197cd605c');
        $this->addSql('DROP INDEX idx_cbe5a33197cd605c');
        $this->addSql('ALTER TABLE book DROP formats_id');
        $this->addSql('ALTER TABLE review ALTER created_at TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('ALTER TABLE review ALTER created_at DROP DEFAULT');
        $this->addSql('COMMENT ON COLUMN review.created_at IS \'(DC2Type:datetime_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE book ADD formats_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE book ADD CONSTRAINT fk_cbe5a33197cd605c FOREIGN KEY (formats_id) REFERENCES book_to_book_format (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_cbe5a33197cd605c ON book (formats_id)');
        $this->addSql('ALTER TABLE review ALTER created_at TYPE DATE');
        $this->addSql('ALTER TABLE review ALTER created_at DROP DEFAULT');
        $this->addSql('COMMENT ON COLUMN review.created_at IS \'(DC2Type:date_immutable)\'');
    }
}
