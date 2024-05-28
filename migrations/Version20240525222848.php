<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240525222848 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        if ($this->connection->getDatabasePlatform()->getName() === 'sqlite') {
            $this->addSql('CREATE TABLE destination (
                id          INTEGER PRIMARY KEY AUTOINCREMENT,
                name        TEXT NOT NULL,
                description TEXT NOT NULL,
                price       REAL NOT NULL,
                duration    INTEGER NOT NULL,
                image       TEXT NOT NULL
            );');
        } else {
            $this->addSql('CREATE TABLE destination(
                id          INT auto_increment NOT NULL,
                name        VARCHAR(255) NOT NULL,
                description LONGTEXT NOT NULL,
                price       DOUBLE PRECISION NOT NULL,
                duration    INT NOT NULL,
                image       LONGTEXT NOT NULL,
                PRIMARY KEY(id)
            )
            DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` engine = innodb');

            $this->addSql('CREATE TABLE messenger_messages (
                id              BIGINT AUTO_INCREMENT NOT NULL, 
                body            LONGTEXT NOT NULL, 
                headers         LONGTEXT NOT NULL,
                queue_name      VARCHAR(190) NOT NULL, 
                created_at      DATETIME NOT NULL, 
                available_at    DATETIME NOT NULL, 
                delivered_at    DATETIME DEFAULT NULL, 
                INDEX IDX_75EA56E0FB7336F0 (queue_name),
                INDEX IDX_75EA56E0E3BD61CE (available_at),
                INDEX IDX_75EA56E016BA31DB (delivered_at),
                PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        }
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE destination');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
