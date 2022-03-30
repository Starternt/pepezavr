<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20220330024041 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE auth_access_token ALTER id TYPE UUID');
        $this->addSql('ALTER TABLE auth_access_token ALTER id DROP DEFAULT');
        $this->addSql('COMMENT ON COLUMN auth_access_token.id IS NULL');
        $this->addSql('ALTER TABLE post_content ALTER id TYPE UUID');
        $this->addSql('ALTER TABLE post_content ALTER id DROP DEFAULT');
        $this->addSql('ALTER TABLE post_content ALTER post_id TYPE UUID');
        $this->addSql('ALTER TABLE post_content ALTER post_id DROP DEFAULT');
        $this->addSql('ALTER TABLE post_content ALTER body TYPE VARCHAR(15000)');
        $this->addSql('COMMENT ON COLUMN post_content.id IS NULL');
        $this->addSql('COMMENT ON COLUMN post_content.post_id IS NULL');
        $this->addSql('ALTER TABLE posts ALTER id TYPE UUID');
        $this->addSql('ALTER TABLE posts ALTER id DROP DEFAULT');
        $this->addSql('COMMENT ON COLUMN posts.id IS NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE auth_access_token ALTER id TYPE UUID');
        $this->addSql('ALTER TABLE auth_access_token ALTER id DROP DEFAULT');
        $this->addSql('COMMENT ON COLUMN auth_access_token.id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE posts ALTER id TYPE UUID');
        $this->addSql('ALTER TABLE posts ALTER id DROP DEFAULT');
        $this->addSql('COMMENT ON COLUMN posts.id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE post_content ALTER id TYPE UUID');
        $this->addSql('ALTER TABLE post_content ALTER id DROP DEFAULT');
        $this->addSql('ALTER TABLE post_content ALTER post_id TYPE UUID');
        $this->addSql('ALTER TABLE post_content ALTER post_id DROP DEFAULT');
        $this->addSql('ALTER TABLE post_content ALTER body TYPE VARCHAR(255)');
        $this->addSql('COMMENT ON COLUMN post_content.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN post_content.post_id IS \'(DC2Type:uuid)\'');
    }
}
