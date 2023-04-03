<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230403200047 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('DROP INDEX unique_currency_per_date ON currency');
        $this->addSql('CREATE UNIQUE INDEX unique_currency_per_date ON currency (currency_code, date)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP INDEX unique_currency_per_date ON currency');
        $this->addSql('CREATE INDEX unique_currency_per_date ON currency (currency_code, date)');
    }
}
