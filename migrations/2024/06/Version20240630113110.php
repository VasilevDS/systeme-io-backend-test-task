<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240630113110 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create "coupon_type" type';
    }

    public function up(Schema $schema): void
    {
        $this->addSql("CREATE TYPE coupon_type AS ENUM('fixed', 'percentage')");
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TYPE coupon_type');
    }
}
