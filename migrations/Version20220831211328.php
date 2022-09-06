<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220831211328 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql("INSERT INTO book_format (id, title, description, comment) VALUES (1, 'eBook', 'Our eBooks come in DRM-free Kindle, ePub, and PDF formats + liveBook, our enhanced eBook format accessible from any web browser.', null)");
        $this->addSql("INSERT INTO book_format (id, title, description, comment) VALUES (2, 'print + eBook', 'Receive a print copy shipped to your door + the eBook in Kindle, ePub, & PDF formats + liveBook, our enhanced eBook format accessible from any web browser.', 'FREE domestic shipping on orders of three or more print books')");
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DELETE FROM book_format');
    }
}
