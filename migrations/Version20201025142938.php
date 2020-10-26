<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201025142938 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bill_product ADD CONSTRAINT FK_278930B21A8C12F5 FOREIGN KEY (bill_id) REFERENCES bill (id)');
        $this->addSql('ALTER TABLE bill_product ADD CONSTRAINT FK_278930B24584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('CREATE INDEX IDX_278930B21A8C12F5 ON bill_product (bill_id)');
        $this->addSql('CREATE INDEX IDX_278930B24584665A ON bill_product (product_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bill_product DROP FOREIGN KEY FK_278930B21A8C12F5');
        $this->addSql('ALTER TABLE bill_product DROP FOREIGN KEY FK_278930B24584665A');
        $this->addSql('DROP INDEX IDX_278930B21A8C12F5 ON bill_product');
        $this->addSql('DROP INDEX IDX_278930B24584665A ON bill_product');
    }
}
