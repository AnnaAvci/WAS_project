<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221101220453 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE service_book (id INT AUTO_INCREMENT NOT NULL, service_client_id INT NOT NULL, service_id INT NOT NULL, message LONGTEXT DEFAULT NULL, date_created DATETIME NOT NULL, date_start DATETIME NOT NULL, date_end DATETIME NOT NULL, is_accepted INT DEFAULT NULL, INDEX IDX_5A617DD417A536B (service_client_id), INDEX IDX_5A617DDED5CA9E6 (service_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE service_book ADD CONSTRAINT FK_5A617DD417A536B FOREIGN KEY (service_client_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE service_book ADD CONSTRAINT FK_5A617DDED5CA9E6 FOREIGN KEY (service_id) REFERENCES service (id)');
        $this->addSql('ALTER TABLE book_service DROP FOREIGN KEY FK_72BDB244417A536B');
        $this->addSql('ALTER TABLE book_service DROP FOREIGN KEY FK_72BDB244ED5CA9E6');
        $this->addSql('DROP TABLE book_service');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE book_service (id INT AUTO_INCREMENT NOT NULL, service_client_id INT NOT NULL, service_id INT NOT NULL, message LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, date_created DATETIME NOT NULL, date_start DATETIME NOT NULL, date_end DATETIME NOT NULL, is_accepted INT DEFAULT NULL, INDEX IDX_72BDB244ED5CA9E6 (service_id), INDEX IDX_72BDB244417A536B (service_client_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE book_service ADD CONSTRAINT FK_72BDB244417A536B FOREIGN KEY (service_client_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE book_service ADD CONSTRAINT FK_72BDB244ED5CA9E6 FOREIGN KEY (service_id) REFERENCES service (id)');
        $this->addSql('ALTER TABLE service_book DROP FOREIGN KEY FK_5A617DD417A536B');
        $this->addSql('ALTER TABLE service_book DROP FOREIGN KEY FK_5A617DDED5CA9E6');
        $this->addSql('DROP TABLE service_book');
    }
}
