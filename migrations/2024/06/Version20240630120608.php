<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240630120608 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create "coupon" table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE coupon (id UUID NOT NULL, name VARCHAR(255) NOT NULL, type coupon_type NOT NULL, value INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN coupon.id IS \'(DC2Type:uuid)\'');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE coupon');
    }
}
