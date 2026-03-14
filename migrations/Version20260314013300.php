<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260314013300 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create news table for club news management';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE news (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, content LONGTEXT NOT NULL, category VARCHAR(10) NOT NULL COMMENT \'Allowed: MATCH, SIGNING, CLUB, EVENT\', image_url VARCHAR(255) DEFAULT NULL, published_at DATETIME NOT NULL, is_featured TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE news');
    }
}
