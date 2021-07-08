<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210707235204 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE carts ADD coupon_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE carts ADD CONSTRAINT FK_4E004AAC66C5951B FOREIGN KEY (coupon_id) REFERENCES coupons (id)');
        $this->addSql('CREATE INDEX IDX_4E004AAC66C5951B ON carts (coupon_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE carts DROP FOREIGN KEY FK_4E004AAC66C5951B');
        $this->addSql('DROP INDEX IDX_4E004AAC66C5951B ON carts');
        $this->addSql('ALTER TABLE carts DROP coupon_id');
    }
}
