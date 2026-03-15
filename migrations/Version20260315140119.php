<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260315140119 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE coach CHANGE `group` `group` VARCHAR(10) NOT NULL');
        $this->addSql('ALTER TABLE news CHANGE category category VARCHAR(10) NOT NULL');
        $this->addSql('ALTER TABLE user ADD name VARCHAR(255) NOT NULL, ADD last_name VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE coach CHANGE `group` `group` VARCHAR(10) NOT NULL COMMENT \'Allowed: GK, DEF, MID, FWD, HEAD\'');
        $this->addSql('ALTER TABLE news CHANGE category category VARCHAR(10) NOT NULL COMMENT \'Allowed: MATCH, SIGNING, CLUB, EVENT\'');
        $this->addSql('ALTER TABLE user DROP name, DROP last_name');
    }
}
