<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190601093845 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE recipe_image ADD recipe_id INT NOT NULL');
        $this->addSql('ALTER TABLE recipe_image ADD CONSTRAINT FK_D32ED04059D8A214 FOREIGN KEY (recipe_id) REFERENCES recipe (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_D32ED04059D8A214 ON recipe_image (recipe_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE recipe_image DROP FOREIGN KEY FK_D32ED04059D8A214');
        $this->addSql('DROP INDEX IDX_D32ED04059D8A214 ON recipe_image');
        $this->addSql('ALTER TABLE recipe_image DROP recipe_id');
    }
}
