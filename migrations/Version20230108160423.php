<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230108160423 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE UNIQUE INDEX UNIQ_16AEB8D42B36786B ON departments (title)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1A9F64E32B36786B ON faculties (title)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_16AEB8D42B36786B ON departments');
        $this->addSql('DROP INDEX UNIQ_1A9F64E32B36786B ON faculties');
    }
}
