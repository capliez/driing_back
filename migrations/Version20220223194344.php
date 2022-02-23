<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220223194344 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE building (id INT AUTO_INCREMENT NOT NULL, guardian_id INT NOT NULL, name VARCHAR(255) NOT NULL, address VARCHAR(255) NOT NULL, postcode VARCHAR(255) NOT NULL, city VARCHAR(255) NOT NULL, country VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, is_enabled TINYINT(1) DEFAULT NULL, updated_at DATETIME NOT NULL, created_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_E16F61D4989D9B62 (slug), INDEX IDX_E16F61D411CC8B0A (guardian_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE revisions (id INT AUTO_INCREMENT NOT NULL, timestamp DATETIME NOT NULL, username VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE building_audit (id INT NOT NULL, rev INT NOT NULL, guardian_id INT DEFAULT NULL, name VARCHAR(255) DEFAULT NULL, address VARCHAR(255) DEFAULT NULL, postcode VARCHAR(255) DEFAULT NULL, city VARCHAR(255) DEFAULT NULL, country VARCHAR(255) DEFAULT NULL, slug VARCHAR(255) DEFAULT NULL, is_enabled TINYINT(1) DEFAULT NULL, updated_at DATETIME DEFAULT NULL, created_at DATETIME DEFAULT NULL, revtype VARCHAR(4) NOT NULL, INDEX rev_d09ea8237d0f3efadd73c7c32a2a77a1_idx (rev), PRIMARY KEY(id, rev)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE language (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, shortname VARCHAR(255) NOT NULL, updated_at DATETIME NOT NULL, created_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE logs (id INT AUTO_INCREMENT NOT NULL, message VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE package (id INT AUTO_INCREMENT NOT NULL, building_id INT NOT NULL, resident_id INT NOT NULL, guardian_id INT NOT NULL, nb_package INT NOT NULL, is_handed_over TINYINT(1) DEFAULT NULL, updated_at DATETIME NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_DE6867954D2A7E12 (building_id), INDEX IDX_DE6867958012C5B0 (resident_id), INDEX IDX_DE68679511CC8B0A (guardian_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE package_audit (id INT NOT NULL, rev INT NOT NULL, building_id INT DEFAULT NULL, resident_id INT DEFAULT NULL, guardian_id INT DEFAULT NULL, nb_package INT DEFAULT NULL, is_handed_over TINYINT(1) DEFAULT NULL, updated_at DATETIME DEFAULT NULL, created_at DATETIME DEFAULT NULL, revtype VARCHAR(4) NOT NULL, INDEX rev_ba01d6ad4595294b3e00d5d42879f098_idx (rev), PRIMARY KEY(id, rev)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE package_detail (id INT AUTO_INCREMENT NOT NULL, package_id INT NOT NULL, is_bulky TINYINT(1) DEFAULT NULL, is_damaged TINYINT(1) DEFAULT NULL, updated_at DATETIME NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_10FD0C4FF44CABFF (package_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE package_detail_audit (id INT NOT NULL, rev INT NOT NULL, package_id INT DEFAULT NULL, is_bulky TINYINT(1) DEFAULT NULL, is_damaged TINYINT(1) DEFAULT NULL, updated_at DATETIME DEFAULT NULL, created_at DATETIME DEFAULT NULL, revtype VARCHAR(4) NOT NULL, INDEX rev_e3b9a10404475a449c680459fba8fa06_idx (rev), PRIMARY KEY(id, rev)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reset_password_request (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, selector VARCHAR(20) NOT NULL, hashed_token VARCHAR(100) NOT NULL, requested_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', expires_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_7CE748AA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE resident (id INT AUTO_INCREMENT NOT NULL, building_id INT NOT NULL, last_name VARCHAR(255) NOT NULL, phone VARCHAR(255) NOT NULL, email VARCHAR(255) DEFAULT NULL, is_enabled TINYINT(1) DEFAULT NULL, updated_at DATETIME NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_1D03DA064D2A7E12 (building_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE resident_audit (id INT NOT NULL, rev INT NOT NULL, building_id INT DEFAULT NULL, last_name VARCHAR(255) DEFAULT NULL, phone VARCHAR(255) DEFAULT NULL, email VARCHAR(255) DEFAULT NULL, is_enabled TINYINT(1) DEFAULT NULL, updated_at DATETIME DEFAULT NULL, created_at DATETIME DEFAULT NULL, revtype VARCHAR(4) NOT NULL, INDEX rev_425bdf22a135cfec27167a4447ee6221_idx (rev), PRIMARY KEY(id, rev)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE role (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(255) NOT NULL, shortname VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `user` (id INT AUTO_INCREMENT NOT NULL, user_role_id INT DEFAULT NULL, language_id INT NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, email VARCHAR(180) DEFAULT NULL, password VARCHAR(255) NOT NULL, phone VARCHAR(255) NOT NULL, auth_code VARCHAR(255) DEFAULT NULL, is_enabled TINYINT(1) DEFAULT NULL, is_onboarding TINYINT(1) DEFAULT NULL, retrieve_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', last_login_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', slug VARCHAR(255) NOT NULL, updated_at DATETIME NOT NULL, created_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), UNIQUE INDEX UNIQ_8D93D649444F97DD (phone), UNIQUE INDEX UNIQ_8D93D649989D9B62 (slug), INDEX IDX_8D93D6498E0E3CA6 (user_role_id), INDEX IDX_8D93D64982F1BAF4 (language_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_audit (id INT NOT NULL, rev INT NOT NULL, user_role_id INT DEFAULT NULL, language_id INT DEFAULT NULL, first_name VARCHAR(255) DEFAULT NULL, last_name VARCHAR(255) DEFAULT NULL, email VARCHAR(180) DEFAULT NULL, password VARCHAR(255) DEFAULT NULL, phone VARCHAR(255) DEFAULT NULL, auth_code VARCHAR(255) DEFAULT NULL, is_enabled TINYINT(1) DEFAULT NULL, is_onboarding TINYINT(1) DEFAULT NULL, retrieve_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', last_login_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', slug VARCHAR(255) DEFAULT NULL, updated_at DATETIME DEFAULT NULL, created_at DATETIME DEFAULT NULL, revtype VARCHAR(4) NOT NULL, INDEX rev_e06395edc291d0719bee26fd39a32e8a_idx (rev), PRIMARY KEY(id, rev)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE rememberme_token (series VARCHAR(88) NOT NULL, value VARCHAR(88) NOT NULL, lastUsed DATETIME NOT NULL, class VARCHAR(100) NOT NULL, username VARCHAR(200) NOT NULL, PRIMARY KEY(series)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE building ADD CONSTRAINT FK_E16F61D411CC8B0A FOREIGN KEY (guardian_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE building_audit ADD CONSTRAINT rev_d09ea8237d0f3efadd73c7c32a2a77a1_fk FOREIGN KEY (rev) REFERENCES revisions (id)');
        $this->addSql('ALTER TABLE package ADD CONSTRAINT FK_DE6867954D2A7E12 FOREIGN KEY (building_id) REFERENCES building (id)');
        $this->addSql('ALTER TABLE package ADD CONSTRAINT FK_DE6867958012C5B0 FOREIGN KEY (resident_id) REFERENCES resident (id)');
        $this->addSql('ALTER TABLE package ADD CONSTRAINT FK_DE68679511CC8B0A FOREIGN KEY (guardian_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE package_audit ADD CONSTRAINT rev_ba01d6ad4595294b3e00d5d42879f098_fk FOREIGN KEY (rev) REFERENCES revisions (id)');
        $this->addSql('ALTER TABLE package_detail ADD CONSTRAINT FK_10FD0C4FF44CABFF FOREIGN KEY (package_id) REFERENCES package (id)');
        $this->addSql('ALTER TABLE package_detail_audit ADD CONSTRAINT rev_e3b9a10404475a449c680459fba8fa06_fk FOREIGN KEY (rev) REFERENCES revisions (id)');
        $this->addSql('ALTER TABLE reset_password_request ADD CONSTRAINT FK_7CE748AA76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE resident ADD CONSTRAINT FK_1D03DA064D2A7E12 FOREIGN KEY (building_id) REFERENCES building (id)');
        $this->addSql('ALTER TABLE resident_audit ADD CONSTRAINT rev_425bdf22a135cfec27167a4447ee6221_fk FOREIGN KEY (rev) REFERENCES revisions (id)');
        $this->addSql('ALTER TABLE `user` ADD CONSTRAINT FK_8D93D6498E0E3CA6 FOREIGN KEY (user_role_id) REFERENCES role (id)');
        $this->addSql('ALTER TABLE `user` ADD CONSTRAINT FK_8D93D64982F1BAF4 FOREIGN KEY (language_id) REFERENCES language (id)');
        $this->addSql('ALTER TABLE user_audit ADD CONSTRAINT rev_e06395edc291d0719bee26fd39a32e8a_fk FOREIGN KEY (rev) REFERENCES revisions (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE package DROP FOREIGN KEY FK_DE6867954D2A7E12');
        $this->addSql('ALTER TABLE resident DROP FOREIGN KEY FK_1D03DA064D2A7E12');
        $this->addSql('ALTER TABLE building_audit DROP FOREIGN KEY rev_d09ea8237d0f3efadd73c7c32a2a77a1_fk');
        $this->addSql('ALTER TABLE package_audit DROP FOREIGN KEY rev_ba01d6ad4595294b3e00d5d42879f098_fk');
        $this->addSql('ALTER TABLE package_detail_audit DROP FOREIGN KEY rev_e3b9a10404475a449c680459fba8fa06_fk');
        $this->addSql('ALTER TABLE resident_audit DROP FOREIGN KEY rev_425bdf22a135cfec27167a4447ee6221_fk');
        $this->addSql('ALTER TABLE user_audit DROP FOREIGN KEY rev_e06395edc291d0719bee26fd39a32e8a_fk');
        $this->addSql('ALTER TABLE `user` DROP FOREIGN KEY FK_8D93D64982F1BAF4');
        $this->addSql('ALTER TABLE package_detail DROP FOREIGN KEY FK_10FD0C4FF44CABFF');
        $this->addSql('ALTER TABLE package DROP FOREIGN KEY FK_DE6867958012C5B0');
        $this->addSql('ALTER TABLE `user` DROP FOREIGN KEY FK_8D93D6498E0E3CA6');
        $this->addSql('ALTER TABLE building DROP FOREIGN KEY FK_E16F61D411CC8B0A');
        $this->addSql('ALTER TABLE package DROP FOREIGN KEY FK_DE68679511CC8B0A');
        $this->addSql('ALTER TABLE reset_password_request DROP FOREIGN KEY FK_7CE748AA76ED395');
        $this->addSql('DROP TABLE building');
        $this->addSql('DROP TABLE revisions');
        $this->addSql('DROP TABLE building_audit');
        $this->addSql('DROP TABLE language');
        $this->addSql('DROP TABLE logs');
        $this->addSql('DROP TABLE package');
        $this->addSql('DROP TABLE package_audit');
        $this->addSql('DROP TABLE package_detail');
        $this->addSql('DROP TABLE package_detail_audit');
        $this->addSql('DROP TABLE reset_password_request');
        $this->addSql('DROP TABLE resident');
        $this->addSql('DROP TABLE resident_audit');
        $this->addSql('DROP TABLE role');
        $this->addSql('DROP TABLE `user`');
        $this->addSql('DROP TABLE user_audit');
        $this->addSql('DROP TABLE rememberme_token');
    }
}
