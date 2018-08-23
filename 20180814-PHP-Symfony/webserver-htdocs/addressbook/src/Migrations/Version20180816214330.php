<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180816214330 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE person (id INT AUTO_INCREMENT NOT NULL, occupation_id INT DEFAULT NULL, lastname VARCHAR(64) NOT NULL, firstname VARCHAR(64) NOT NULL, telephone VARCHAR(32) DEFAULT NULL, email VARCHAR(128) DEFAULT NULL, retired TINYINT(1) NOT NULL, comment LONGTEXT DEFAULT NULL, createtimestamp DATETIME NOT NULL, INDEX IDX_34DCD17622C8FC20 (occupation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE occupation (id INT AUTO_INCREMENT NOT NULL, displayname VARCHAR(128) NOT NULL, url VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE person ADD CONSTRAINT FK_34DCD17622C8FC20 FOREIGN KEY (occupation_id) REFERENCES occupation (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE person DROP FOREIGN KEY FK_34DCD17622C8FC20');
        $this->addSql('DROP TABLE person');
        $this->addSql('DROP TABLE occupation');
    }
}
