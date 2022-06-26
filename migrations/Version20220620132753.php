<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220620132753 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE book ADD user_id INT NOT NULL');
        $this->addSql('ALTER TABLE book ALTER image DROP NOT NULL');
        $this->addSql('ALTER TABLE book ALTER authors DROP NOT NULL');
        $this->addSql('ALTER TABLE book ALTER publication_date DROP NOT NULL');
        $this->addSql('ALTER TABLE book ALTER isbn DROP NOT NULL');
        $this->addSql('ALTER TABLE book ALTER description DROP NOT NULL');
        $this->addSql('ALTER TABLE book ADD CONSTRAINT FK_CBE5A331A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_CBE5A331A76ED395 ON book (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE book DROP CONSTRAINT FK_CBE5A331A76ED395');
        $this->addSql('DROP INDEX IDX_CBE5A331A76ED395');
        $this->addSql('ALTER TABLE book DROP user_id');
        $this->addSql('ALTER TABLE book ALTER image SET NOT NULL');
        $this->addSql('ALTER TABLE book ALTER authors SET NOT NULL');
        $this->addSql('ALTER TABLE book ALTER isbn SET NOT NULL');
        $this->addSql('ALTER TABLE book ALTER description SET NOT NULL');
        $this->addSql('ALTER TABLE book ALTER publication_date SET NOT NULL');
    }
}
