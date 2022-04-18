<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220417200644 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE fruit_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE fruit_nutrient_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE salad_nutrient_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE fruit (id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE fruit_nutrient (id INT NOT NULL, fruit_id INT NOT NULL, name VARCHAR(255) NOT NULL, weight DOUBLE PRECISION NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_768172B8BAC115F0 ON fruit_nutrient (fruit_id)');
        $this->addSql('CREATE TABLE salad (id SERIAL NOT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE salad_fruit (fruit_id INT NOT NULL, salad_id INT NOT NULL, weight DOUBLE PRECISION NOT NULL, PRIMARY KEY(fruit_id, salad_id))');
        $this->addSql('CREATE INDEX IDX_707CCF9BBAC115F0 ON salad_fruit (fruit_id)');
        $this->addSql('CREATE INDEX IDX_707CCF9B9C1CE3E1 ON salad_fruit (salad_id)');
        $this->addSql('CREATE TABLE salad_nutrient (id INT NOT NULL, salad_id INT NOT NULL, name VARCHAR(255) NOT NULL, weight DOUBLE PRECISION NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_91815D6D9C1CE3E1 ON salad_nutrient (salad_id)');
        $this->addSql('ALTER TABLE fruit_nutrient ADD CONSTRAINT FK_768172B8BAC115F0 FOREIGN KEY (fruit_id) REFERENCES fruit (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE salad_fruit ADD CONSTRAINT FK_707CCF9BBAC115F0 FOREIGN KEY (fruit_id) REFERENCES fruit (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE salad_fruit ADD CONSTRAINT FK_707CCF9B9C1CE3E1 FOREIGN KEY (salad_id) REFERENCES salad (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE salad_nutrient ADD CONSTRAINT FK_91815D6D9C1CE3E1 FOREIGN KEY (salad_id) REFERENCES salad (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE fruit_nutrient DROP CONSTRAINT FK_768172B8BAC115F0');
        $this->addSql('ALTER TABLE salad_fruit DROP CONSTRAINT FK_707CCF9BBAC115F0');
        $this->addSql('ALTER TABLE salad_fruit DROP CONSTRAINT FK_707CCF9B9C1CE3E1');
        $this->addSql('ALTER TABLE salad_nutrient DROP CONSTRAINT FK_91815D6D9C1CE3E1');
        $this->addSql('DROP SEQUENCE fruit_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE fruit_nutrient_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE salad_nutrient_id_seq CASCADE');
        $this->addSql('DROP TABLE fruit');
        $this->addSql('DROP TABLE fruit_nutrient');
        $this->addSql('DROP TABLE salad');
        $this->addSql('DROP TABLE salad_fruit');
        $this->addSql('DROP TABLE salad_nutrient');
    }
}
