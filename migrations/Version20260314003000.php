<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260314003000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create coach table for technical staff management';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE coach (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, surname VARCHAR(255) NOT NULL, role VARCHAR(255) NOT NULL, `group` VARCHAR(10) NOT NULL COMMENT \'Allowed: GK, DEF, MID, FWD, HEAD\', image_url VARCHAR(255) NOT NULL, joined_at DATETIME NOT NULL, left_at DATETIME DEFAULT NULL, bio LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE coach');
    }
}
