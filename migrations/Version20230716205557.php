<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230716205557 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE build (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) NOT NULL, difficulty INT DEFAULT NULL, description LONGTEXT NOT NULL, price DOUBLE PRECISION DEFAULT NULL, is_favorite TINYINT(1) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE build_item (build_id INT NOT NULL, item_id INT NOT NULL, INDEX IDX_186748F417C13F8B (build_id), INDEX IDX_186748F4126F525E (item_id), PRIMARY KEY(build_id, item_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE build_item ADD CONSTRAINT FK_186748F417C13F8B FOREIGN KEY (build_id) REFERENCES build (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE build_item ADD CONSTRAINT FK_186748F4126F525E FOREIGN KEY (item_id) REFERENCES item (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE build_item DROP FOREIGN KEY FK_186748F417C13F8B');
        $this->addSql('ALTER TABLE build_item DROP FOREIGN KEY FK_186748F4126F525E');
        $this->addSql('DROP TABLE build');
        $this->addSql('DROP TABLE build_item');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
