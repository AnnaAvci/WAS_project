<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221101213424 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE location_book (id INT AUTO_INCREMENT NOT NULL, location_client_id INT NOT NULL, location_id INT NOT NULL, message LONGTEXT DEFAULT NULL, date_created DATETIME NOT NULL, date_start DATETIME NOT NULL, date_end DATETIME NOT NULL, is_accepted INT DEFAULT NULL, INDEX IDX_4BE11671573EBF57 (location_client_id), INDEX IDX_4BE1167164D218E (location_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE location_book ADD CONSTRAINT FK_4BE11671573EBF57 FOREIGN KEY (location_client_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE location_book ADD CONSTRAINT FK_4BE1167164D218E FOREIGN KEY (location_id) REFERENCES location (id)');
        $this->addSql('ALTER TABLE book_location DROP FOREIGN KEY FK_47619F92573EBF57');
        $this->addSql('ALTER TABLE book_location DROP FOREIGN KEY FK_47619F9264D218E');
        $this->addSql('DROP TABLE book_location');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE book_location (id INT AUTO_INCREMENT NOT NULL, location_client_id INT NOT NULL, location_id INT NOT NULL, message LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, date_created DATETIME NOT NULL, date_start DATETIME NOT NULL, date_end DATETIME NOT NULL, is_accepted INT DEFAULT NULL, INDEX IDX_47619F9264D218E (location_id), INDEX IDX_47619F92573EBF57 (location_client_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE book_location ADD CONSTRAINT FK_47619F92573EBF57 FOREIGN KEY (location_client_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE book_location ADD CONSTRAINT FK_47619F9264D218E FOREIGN KEY (location_id) REFERENCES location (id)');
        $this->addSql('ALTER TABLE location_book DROP FOREIGN KEY FK_4BE11671573EBF57');
        $this->addSql('ALTER TABLE location_book DROP FOREIGN KEY FK_4BE1167164D218E');
        $this->addSql('DROP TABLE location_book');
    }
}
