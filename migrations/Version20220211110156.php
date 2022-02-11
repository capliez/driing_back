<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220211110156 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE package_detail (id INT AUTO_INCREMENT NOT NULL, package_id INT NOT NULL, is_bulky TINYINT(1) DEFAULT NULL, is_damaged TINYINT(1) DEFAULT NULL, updated_at DATETIME NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_10FD0C4FF44CABFF (package_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE package_detail ADD CONSTRAINT FK_10FD0C4FF44CABFF FOREIGN KEY (package_id) REFERENCES package (id)');
        $this->addSql('ALTER TABLE logs CHANGE prev_data prev_data JSON DEFAULT NULL');
        $this->addSql('ALTER TABLE package ADD building_id INT NOT NULL, ADD resident_id INT NOT NULL, ADD guardian_id INT NOT NULL, ADD nb_package INT NOT NULL, ADD updated_at DATETIME NOT NULL, ADD created_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE package ADD CONSTRAINT FK_DE6867954D2A7E12 FOREIGN KEY (building_id) REFERENCES building (id)');
        $this->addSql('ALTER TABLE package ADD CONSTRAINT FK_DE6867958012C5B0 FOREIGN KEY (resident_id) REFERENCES resident (id)');
        $this->addSql('ALTER TABLE package ADD CONSTRAINT FK_DE68679511CC8B0A FOREIGN KEY (guardian_id) REFERENCES `user` (id)');
        $this->addSql('CREATE INDEX IDX_DE6867954D2A7E12 ON package (building_id)');
        $this->addSql('CREATE INDEX IDX_DE6867958012C5B0 ON package (resident_id)');
        $this->addSql('CREATE INDEX IDX_DE68679511CC8B0A ON package (guardian_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE package_detail');
        $this->addSql('ALTER TABLE building CHANGE name name VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE address address VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE postcode postcode VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE city city VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE country country VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE slug slug VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE language CHANGE name name VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE shortname shortname VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE logs CHANGE name_entity name_entity VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE action action VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE prev_data prev_data JSON NOT NULL');
        $this->addSql('ALTER TABLE package DROP FOREIGN KEY FK_DE6867954D2A7E12');
        $this->addSql('ALTER TABLE package DROP FOREIGN KEY FK_DE6867958012C5B0');
        $this->addSql('ALTER TABLE package DROP FOREIGN KEY FK_DE68679511CC8B0A');
        $this->addSql('DROP INDEX IDX_DE6867954D2A7E12 ON package');
        $this->addSql('DROP INDEX IDX_DE6867958012C5B0 ON package');
        $this->addSql('DROP INDEX IDX_DE68679511CC8B0A ON package');
        $this->addSql('ALTER TABLE package DROP building_id, DROP resident_id, DROP guardian_id, DROP nb_package, DROP updated_at, DROP created_at');
        $this->addSql('ALTER TABLE rememberme_token CHANGE series series VARCHAR(88) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE value value VARCHAR(88) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE class class VARCHAR(100) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE username username VARCHAR(200) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE reset_password_request CHANGE selector selector VARCHAR(20) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE hashed_token hashed_token VARCHAR(100) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE resident CHANGE last_name last_name VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE phone phone VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE email email VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE role CHANGE label label VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE shortname shortname VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE `user` CHANGE first_name first_name VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE last_name last_name VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE email email VARCHAR(180) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE password password VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE phone phone VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE auth_code auth_code VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE slug slug VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
