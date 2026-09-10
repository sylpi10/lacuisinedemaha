<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260910222559 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE contact (id INT AUTO_INCREMENT NOT NULL, sender_name VARCHAR(255) NOT NULL, sender_email VARCHAR(255) NOT NULL, sender_phone VARCHAR(100) NOT NULL, sender_wished_number INT NOT NULL, sender_date DATE NOT NULL, sender_message LONGTEXT NOT NULL, wished_formula_id INT DEFAULT NULL, INDEX IDX_4C62E6385418FD3E (wished_formula_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE formulas (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(100) NOT NULL, description LONGTEXT NOT NULL, price VARCHAR(100) NOT NULL, item_list LONGTEXT NOT NULL, image_id INT DEFAULT NULL, INDEX IDX_81D64DE53DA5256D (image_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE galery (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) DEFAULT NULL, description LONGTEXT DEFAULT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE image (id INT AUTO_INCREMENT NOT NULL, filename VARCHAR(255) NOT NULL, path VARCHAR(255) NOT NULL, alt_text VARCHAR(255) DEFAULT NULL, position INT DEFAULT NULL, created_at DATETIME NOT NULL, gallery_id INT DEFAULT NULL, INDEX IDX_C53D045F4E7AF8F (gallery_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE presentation (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(100) DEFAULT NULL, subtitle VARCHAR(100) DEFAULT NULL, description LONGTEXT NOT NULL, image_id INT DEFAULT NULL, INDEX IDX_9B66E8933DA5256D (image_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_USERNAME (username), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE contact ADD CONSTRAINT FK_4C62E6385418FD3E FOREIGN KEY (wished_formula_id) REFERENCES formulas (id)');
        $this->addSql('ALTER TABLE formulas ADD CONSTRAINT FK_81D64DE53DA5256D FOREIGN KEY (image_id) REFERENCES image (id)');
        $this->addSql('ALTER TABLE image ADD CONSTRAINT FK_C53D045F4E7AF8F FOREIGN KEY (gallery_id) REFERENCES galery (id)');
        $this->addSql('ALTER TABLE presentation ADD CONSTRAINT FK_9B66E8933DA5256D FOREIGN KEY (image_id) REFERENCES image (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE contact DROP FOREIGN KEY FK_4C62E6385418FD3E');
        $this->addSql('ALTER TABLE formulas DROP FOREIGN KEY FK_81D64DE53DA5256D');
        $this->addSql('ALTER TABLE image DROP FOREIGN KEY FK_C53D045F4E7AF8F');
        $this->addSql('ALTER TABLE presentation DROP FOREIGN KEY FK_9B66E8933DA5256D');
        $this->addSql('DROP TABLE contact');
        $this->addSql('DROP TABLE formulas');
        $this->addSql('DROP TABLE galery');
        $this->addSql('DROP TABLE image');
        $this->addSql('DROP TABLE presentation');
        $this->addSql('DROP TABLE user');
    }
}
