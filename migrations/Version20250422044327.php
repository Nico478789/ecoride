<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250422044327 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE car ADD brand_name VARCHAR(32) NOT NULL, ADD model_name VARCHAR(32) NOT NULL, ADD color VARCHAR(32) NOT NULL, ADD first_registration_date DATE NOT NULL, ADD registration_number VARCHAR(32) NOT NULL, ADD electric TINYINT(1) NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE ride ADD car_id INT NOT NULL, ADD departure_time DATETIME NOT NULL, ADD arrival_time DATETIME NOT NULL, ADD number_of_seats SMALLINT NOT NULL, ADD price INT NOT NULL, ADD status VARCHAR(32) NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE ride ADD CONSTRAINT FK_9B3D7CD0C3C6F69F FOREIGN KEY (car_id) REFERENCES car (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_9B3D7CD0C3C6F69F ON ride (car_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user ADD nickname VARCHAR(32) DEFAULT NULL, ADD credits INT NOT NULL, ADD phone_number VARCHAR(32) DEFAULT NULL
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE car DROP brand_name, DROP model_name, DROP color, DROP first_registration_date, DROP registration_number, DROP electric
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE ride DROP FOREIGN KEY FK_9B3D7CD0C3C6F69F
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_9B3D7CD0C3C6F69F ON ride
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE ride DROP car_id, DROP departure_time, DROP arrival_time, DROP number_of_seats, DROP price, DROP status
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE `user` DROP nickname, DROP credits, DROP phone_number
        SQL);
    }
}
