<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241126081743 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, profile_picture VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE category ADD name VARCHAR(255) NOT NULL, ADD description LONGTEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE comment ADD content LONGTEXT NOT NULL, ADD created_at DATETIME NOT NULL, ADD status VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE post ADD title VARCHAR(255) NOT NULL, ADD content LONGTEXT NOT NULL, ADD published_at DATETIME NOT NULL, ADD picture VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE user');
        $this->addSql('ALTER TABLE category DROP name, DROP description');
        $this->addSql('ALTER TABLE comment DROP content, DROP created_at, DROP status');
        $this->addSql('ALTER TABLE post DROP title, DROP content, DROP published_at, DROP picture');
    }
}
