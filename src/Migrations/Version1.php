<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version1 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE "user" (id SERIAL NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, firstname VARCHAR(30) DEFAULT NULL, lastname VARCHAR(30) NOT NULL, reset_token VARCHAR(255) DEFAULT NULL, disabled BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON "user" (email)');
        $this->addSql('CREATE TABLE verbe (id SERIAL NOT NULL, lang VARCHAR(2) NOT NULL, fr VARCHAR(50) NOT NULL, infinitif VARCHAR(50) NOT NULL, form1 VARCHAR(50) DEFAULT NULL, form2 VARCHAR(50) DEFAULT NULL, level INT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE logs (id SERIAL NOT NULL, username VARCHAR(180) NOT NULL, user_id INT NOT NULL, last_login TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, ip VARCHAR(15) NOT NULL, success BOOLEAN NOT NULL, reason VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE score (id SERIAL NOT NULL, player_id INT NOT NULL, date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, game INT NOT NULL, value INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE score ADD CONSTRAINT FK_3299375199E6F5DF FOREIGN KEY (player_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP TABLE "user"');
        $this->addSql('DROP TABLE verbe');
        $this->addSql('DROP TABLE logs');
        $this->addSql('DROP TABLE score');
    }
}
