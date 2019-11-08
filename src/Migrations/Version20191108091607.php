<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191108091607 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE prescription DROP FOREIGN KEY FK_1FBFB8D94D59B30C');
        $this->addSql('ALTER TABLE prescription_product DROP FOREIGN KEY FK_A4E628D393DB413D');
        $this->addSql('DROP TABLE doctor');
        $this->addSql('DROP TABLE prescription');
        $this->addSql('DROP TABLE prescription_product');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE doctor (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE prescription (id INT AUTO_INCREMENT NOT NULL, nuser_id INT DEFAULT NULL, ndoctor_id INT DEFAULT NULL, duration VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, INDEX IDX_1FBFB8D94D59B30C (ndoctor_id), INDEX IDX_1FBFB8D99A41610B (nuser_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE prescription_product (prescription_id INT NOT NULL, product_id INT NOT NULL, INDEX IDX_A4E628D393DB413D (prescription_id), INDEX IDX_A4E628D34584665A (product_id), PRIMARY KEY(prescription_id, product_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE prescription ADD CONSTRAINT FK_1FBFB8D94D59B30C FOREIGN KEY (ndoctor_id) REFERENCES doctor (id)');
        $this->addSql('ALTER TABLE prescription ADD CONSTRAINT FK_1FBFB8D99A41610B FOREIGN KEY (nuser_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE prescription_product ADD CONSTRAINT FK_A4E628D34584665A FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE prescription_product ADD CONSTRAINT FK_A4E628D393DB413D FOREIGN KEY (prescription_id) REFERENCES prescription (id) ON DELETE CASCADE');
    }
}
