<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20200919120048 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Creates near_earth_object table';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE near_earth_object (id INT AUTO_INCREMENT NOT NULL, approach_date DATE NOT NULL, reference VARCHAR(50) NOT NULL, name VARCHAR(50) NOT NULL, speed NUMERIC(20, 10) NOT NULL, is_hazardous TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_D7ACB890AEA34913 (reference), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE near_earth_object');
    }
}
