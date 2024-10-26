<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241025161352 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE events (id INT AUTO_INCREMENT NOT NULL, organized_by_id INT NOT NULL, name VARCHAR(255) NOT NULL, start_at DATETIME NOT NULL, end_at DATETIME NOT NULL, sport LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', localisation VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, max_participants INT NOT NULL, participants_visible TINYINT(1) DEFAULT 1 NOT NULL, price DOUBLE PRECISION DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_5387574A36217ECD (organized_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE partications (id INT AUTO_INCREMENT NOT NULL, user_id_id INT NOT NULL, event_id_id INT NOT NULL, INDEX IDX_1CE1EC3C9D86650F (user_id_id), INDEX IDX_1CE1EC3C3E5F2F7B (event_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE events ADD CONSTRAINT FK_5387574A36217ECD FOREIGN KEY (organized_by_id) REFERENCES utilisateurs (id)');
        $this->addSql('ALTER TABLE partications ADD CONSTRAINT FK_1CE1EC3C9D86650F FOREIGN KEY (user_id_id) REFERENCES utilisateurs (id)');
        $this->addSql('ALTER TABLE partications ADD CONSTRAINT FK_1CE1EC3C3E5F2F7B FOREIGN KEY (event_id_id) REFERENCES events (id)');
        $this->addSql('ALTER TABLE event DROP FOREIGN KEY FK_3BAE0AA736217ECD');
        $this->addSql('ALTER TABLE partication DROP FOREIGN KEY FK_82E72E043E5F2F7B');
        $this->addSql('ALTER TABLE partication DROP FOREIGN KEY FK_82E72E049D86650F');
        $this->addSql('DROP TABLE event');
        $this->addSql('DROP TABLE partication');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE event (id INT AUTO_INCREMENT NOT NULL, organized_by_id INT NOT NULL, name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, start_at DATETIME NOT NULL, end_at DATETIME NOT NULL, sport LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'(DC2Type:array)\', localisation VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, description VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, max_participants INT NOT NULL, participants_visible TINYINT(1) DEFAULT 1 NOT NULL, price DOUBLE PRECISION DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_3BAE0AA736217ECD (organized_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE partication (id INT AUTO_INCREMENT NOT NULL, user_id_id INT NOT NULL, event_id_id INT NOT NULL, INDEX IDX_82E72E049D86650F (user_id_id), INDEX IDX_82E72E043E5F2F7B (event_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA736217ECD FOREIGN KEY (organized_by_id) REFERENCES utilisateurs (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE partication ADD CONSTRAINT FK_82E72E043E5F2F7B FOREIGN KEY (event_id_id) REFERENCES event (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE partication ADD CONSTRAINT FK_82E72E049D86650F FOREIGN KEY (user_id_id) REFERENCES utilisateurs (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE events DROP FOREIGN KEY FK_5387574A36217ECD');
        $this->addSql('ALTER TABLE partications DROP FOREIGN KEY FK_1CE1EC3C9D86650F');
        $this->addSql('ALTER TABLE partications DROP FOREIGN KEY FK_1CE1EC3C3E5F2F7B');
        $this->addSql('DROP TABLE events');
        $this->addSql('DROP TABLE partications');
    }
}
