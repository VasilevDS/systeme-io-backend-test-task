<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240810102057 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create "coupon" table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE coupon (id UUID NOT NULL, code VARCHAR(255) NOT NULL, discr VARCHAR(255) NOT NULL, discount_value INT DEFAULT NULL, discount_percent SMALLINT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_64BF3F0277153098 ON coupon (code)');
        $this->addSql('COMMENT ON COLUMN coupon.id IS \'(DC2Type:uuid)\'');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE coupon');
    }
}
