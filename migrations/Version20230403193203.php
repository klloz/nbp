<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230403193203 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE currency (
                id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', 
                name VARCHAR(64) NOT NULL, 
                currency_code VARCHAR(3) NOT NULL, 
                exchange_rate INT NOT NULL, 
                date DATE NOT NULL COMMENT \'(DC2Type:date_immutable)\', 
                PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB'
        );
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE currency');
    }
}
