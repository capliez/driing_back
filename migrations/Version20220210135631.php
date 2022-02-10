<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220210135631 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE building (id INT AUTO_INCREMENT NOT NULL, guardian_id INT NOT NULL, name VARCHAR(255) NOT NULL, address VARCHAR(255) NOT NULL, postcode VARCHAR(255) NOT NULL, city VARCHAR(255) NOT NULL, country VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, updated_at DATETIME NOT NULL, created_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_E16F61D4989D9B62 (slug), INDEX IDX_E16F61D411CC8B0A (guardian_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE package (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE resident (id INT AUTO_INCREMENT NOT NULL, building_id INT NOT NULL, last_name VARCHAR(255) NOT NULL, phone VARCHAR(255) NOT NULL, email VARCHAR(255) DEFAULT NULL, updated_at DATETIME NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_1D03DA064D2A7E12 (building_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE building ADD CONSTRAINT FK_E16F61D411CC8B0A FOREIGN KEY (guardian_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE resident ADD CONSTRAINT FK_1D03DA064D2A7E12 FOREIGN KEY (building_id) REFERENCES building (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE resident DROP FOREIGN KEY FK_1D03DA064D2A7E12');
        $this->addSql('DROP TABLE building');
        $this->addSql('DROP TABLE package');
        $this->addSql('DROP TABLE resident');
        $this->addSql('ALTER TABLE language CHANGE name name VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE shortname shortname VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE rememberme_token CHANGE series series VARCHAR(88) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE value value VARCHAR(88) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE class class VARCHAR(100) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE username username VARCHAR(200) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE reset_password_request CHANGE selector selector VARCHAR(20) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE hashed_token hashed_token VARCHAR(100) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE role CHANGE label label VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE shortname shortname VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE `user` CHANGE first_name first_name VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE last_name last_name VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE email email VARCHAR(180) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE password password VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE phone phone VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE auth_code auth_code VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE slug slug VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
