<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260311101356 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE book_history DROP FOREIGN KEY `FK_B49A58DD16A2B381`');
        $this->addSql('ALTER TABLE book_history ADD CONSTRAINT FK_B49A58DD16A2B381 FOREIGN KEY (book_id) REFERENCES book (id) ON DELETE SET NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE book_history DROP FOREIGN KEY FK_B49A58DD16A2B381');
        $this->addSql('ALTER TABLE book_history ADD CONSTRAINT `FK_B49A58DD16A2B381` FOREIGN KEY (book_id) REFERENCES book (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
    }
}
