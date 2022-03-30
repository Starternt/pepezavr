<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20220320220053 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE post_content (id UUID NOT NULL, post_id UUID DEFAULT NULL, type VARCHAR(255) NOT NULL, body VARCHAR(255) DEFAULT NULL, position INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_C32D879A4B89032C ON post_content (post_id)');
        $this->addSql('CREATE INDEX IDX_Position ON post_content (position)');
        $this->addSql('CREATE INDEX IDX_Type ON post_content (type)');
        $this->addSql('CREATE TABLE posts (id UUID NOT NULL, created_by INT NOT NULL, title VARCHAR(500) NOT NULL, rating INT DEFAULT 0 NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_885DBAFADE12AB56 ON posts (created_by)');
        $this->addSql('CREATE INDEX IDX_Created_At ON posts (created_at)');
        $this->addSql('CREATE INDEX IDX_Rating ON posts (rating)');
        $this->addSql('CREATE INDEX IDX_Title ON posts (title)');
        $this->addSql('COMMENT ON COLUMN posts.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN posts.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE post_content ADD CONSTRAINT FK_C32D879A4B89032C FOREIGN KEY (post_id) REFERENCES posts (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE posts ADD CONSTRAINT FK_885DBAFADE12AB56 FOREIGN KEY (created_by) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE auth_access_token ALTER id TYPE UUID');
        $this->addSql('ALTER TABLE auth_access_token ALTER id DROP DEFAULT');
        $this->addSql('COMMENT ON COLUMN auth_access_token.id IS NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE post_content DROP CONSTRAINT FK_C32D879A4B89032C');
        $this->addSql('DROP TABLE post_content');
        $this->addSql('DROP TABLE posts');
        $this->addSql('ALTER TABLE auth_access_token ALTER id TYPE UUID');
        $this->addSql('ALTER TABLE auth_access_token ALTER id DROP DEFAULT');
        $this->addSql('COMMENT ON COLUMN auth_access_token.id IS \'(DC2Type:uuid)\'');
    }
}
