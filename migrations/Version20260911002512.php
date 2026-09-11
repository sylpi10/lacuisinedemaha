<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260911002512 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE formulas DROP FOREIGN KEY `FK_81D64DE53DA5256D`');
        $this->addSql('ALTER TABLE presentation DROP FOREIGN KEY `FK_9B66E8933DA5256D`');
        $this->addSql('DROP TABLE image');
        $this->addSql('CREATE TABLE galery_image (id INT AUTO_INCREMENT NOT NULL, image VARCHAR(255) NOT NULL, position INT DEFAULT NULL, galery_id INT NOT NULL, INDEX IDX_2286070DA40A005 (galery_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE galery_image ADD CONSTRAINT FK_2286070DA40A005 FOREIGN KEY (galery_id) REFERENCES galery (id)');
        $this->addSql('DROP INDEX IDX_81D64DE53DA5256D ON formulas');
        $this->addSql('ALTER TABLE formulas ADD image VARCHAR(255) DEFAULT NULL, DROP image_id');
        $this->addSql('ALTER TABLE galery ADD homepage_title VARCHAR(255) DEFAULT NULL, ADD gallery_name VARCHAR(255) DEFAULT NULL, ADD gallery_title VARCHAR(255) DEFAULT NULL, CHANGE title homepage_name VARCHAR(255) DEFAULT NULL');
        $this->addSql('DROP INDEX IDX_9B66E8933DA5256D ON presentation');
        $this->addSql('ALTER TABLE presentation ADD image VARCHAR(255) DEFAULT NULL, DROP image_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE image (id INT AUTO_INCREMENT NOT NULL, filename VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_uca1400_ai_ci`, path VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_uca1400_ai_ci`, alt_text VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_uca1400_ai_ci`, position INT DEFAULT NULL, created_at DATETIME NOT NULL, gallery_id INT DEFAULT NULL, INDEX IDX_C53D045F4E7AF8F (gallery_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_uca1400_ai_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE image ADD CONSTRAINT `FK_C53D045F4E7AF8F` FOREIGN KEY (gallery_id) REFERENCES galery (id)');
        $this->addSql('ALTER TABLE galery_image DROP FOREIGN KEY FK_2286070DA40A005');
        $this->addSql('DROP TABLE galery_image');
        $this->addSql('ALTER TABLE formulas ADD image_id INT DEFAULT NULL, DROP image');
        $this->addSql('ALTER TABLE formulas ADD CONSTRAINT `FK_81D64DE53DA5256D` FOREIGN KEY (image_id) REFERENCES image (id)');
        $this->addSql('CREATE INDEX IDX_81D64DE53DA5256D ON formulas (image_id)');
        $this->addSql('ALTER TABLE galery ADD title VARCHAR(255) DEFAULT NULL, DROP homepage_name, DROP homepage_title, DROP gallery_name, DROP gallery_title');
        $this->addSql('ALTER TABLE presentation ADD image_id INT DEFAULT NULL, DROP image');
        $this->addSql('ALTER TABLE presentation ADD CONSTRAINT `FK_9B66E8933DA5256D` FOREIGN KEY (image_id) REFERENCES image (id)');
        $this->addSql('CREATE INDEX IDX_9B66E8933DA5256D ON presentation (image_id)');
    }
}
