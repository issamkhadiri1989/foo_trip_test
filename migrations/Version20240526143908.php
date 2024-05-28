<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240526143908 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        if ($this->connection->getDatabasePlatform()->getName() === 'sqlite') {
            $this->addSql('CREATE TABLE user (
              id        INTEGER PRIMARY KEY AUTOINCREMENT,
              username  TEXT NOT NULL UNIQUE,
              roles     TEXT NOT NULL,
              password  TEXT NOT NULL
            );');
        } else {
            $this->addSql('CREATE TABLE user
            (
                 id       INT auto_increment NOT NULL,
                 username VARCHAR(180) NOT NULL,
                 roles    JSON NOT NULL,
                 password VARCHAR(255) NOT NULL,
                 UNIQUE INDEX uniq_identifier_username (username),
                 PRIMARY KEY(id)
            )
            DEFAULT CHARACTER SET utf8mb4
            COLLATE `utf8mb4_unicode_ci`
            engine = innodb;');
        }
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE user');
    }
}
