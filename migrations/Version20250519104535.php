<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250519104535 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE book (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, author VARCHAR(255) NOT NULL, is_available TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE loan (id INT AUTO_INCREMENT NOT NULL, book_id INT NOT NULL, user_id INT DEFAULT NULL, due_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', returned_at DATETIME DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)', INDEX IDX_C5D30D0316A2B381 (book_id), INDEX IDX_C5D30D03A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE loan ADD CONSTRAINT FK_C5D30D0316A2B381 FOREIGN KEY (book_id) REFERENCES book (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE loan ADD CONSTRAINT FK_C5D30D03A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE loan DROP FOREIGN KEY FK_C5D30D0316A2B381
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE loan DROP FOREIGN KEY FK_C5D30D03A76ED395
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE book
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE loan
        SQL);
    }
}
