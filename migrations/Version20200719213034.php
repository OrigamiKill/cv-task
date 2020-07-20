<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20200719213034 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        $this->addSql('CREATE TABLE contact (id INT AUTO_INCREMENT NOT NULL, project_id INT DEFAULT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, phone VARCHAR(255) NOT NULL, INDEX IDX_4C62E638166D1F9C (project_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE project (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) NOT NULL, code VARCHAR(255) NOT NULL, url VARCHAR(255) NOT NULL, budget INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE contact ADD CONSTRAINT FK_4C62E638166D1F9C FOREIGN KEY (project_id) REFERENCES project (id)');}

    public function down(Schema $schema) : void
    {
        $this->addSql('ALTER TABLE contact DROP FOREIGN KEY FK_4C62E638166D1F9C');
        $this->addSql('DROP TABLE contact');
        $this->addSql('DROP TABLE project');}
}
