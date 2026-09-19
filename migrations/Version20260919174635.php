<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260919174635 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE faq (id INT AUTO_INCREMENT NOT NULL, subtitle VARCHAR(255) DEFAULT NULL, title VARCHAR(255) DEFAULT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE faq_item (id INT AUTO_INCREMENT NOT NULL, question VARCHAR(255) NOT NULL, answer LONGTEXT NOT NULL, position INT DEFAULT NULL, faq_id INT NOT NULL, INDEX IDX_1A054D7881BEC8C2 (faq_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE formulas_header (id INT AUTO_INCREMENT NOT NULL, subtitle VARCHAR(255) DEFAULT NULL, title VARCHAR(255) DEFAULT NULL, description LONGTEXT DEFAULT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE home_hero (id INT AUTO_INCREMENT NOT NULL, subtitle VARCHAR(255) DEFAULT NULL, title VARCHAR(255) DEFAULT NULL, description LONGTEXT DEFAULT NULL, primary_button_label VARCHAR(255) DEFAULT NULL, secondary_button_label VARCHAR(255) DEFAULT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE faq_item ADD CONSTRAINT FK_1A054D7881BEC8C2 FOREIGN KEY (faq_id) REFERENCES faq (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE faq_item DROP FOREIGN KEY FK_1A054D7881BEC8C2');
        $this->addSql('DROP TABLE faq');
        $this->addSql('DROP TABLE faq_item');
        $this->addSql('DROP TABLE formulas_header');
        $this->addSql('DROP TABLE home_hero');
    }
}
