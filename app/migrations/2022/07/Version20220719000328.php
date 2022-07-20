<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20220719000328 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE SEQUENCE media_object_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE media_object (id INT NOT NULL, file_path VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE post_content ADD image_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE post_content ALTER id TYPE UUID');
        $this->addSql('ALTER TABLE post_content ALTER id DROP DEFAULT');
        $this->addSql('ALTER TABLE post_content ALTER post_id TYPE UUID');
        $this->addSql('ALTER TABLE post_content ALTER post_id DROP DEFAULT');
        $this->addSql('COMMENT ON COLUMN post_content.id IS NULL');
        $this->addSql('COMMENT ON COLUMN post_content.post_id IS NULL');
        $this->addSql('ALTER TABLE post_content ADD CONSTRAINT FK_C32D879A3DA5256D FOREIGN KEY (image_id) REFERENCES media_object (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_C32D879A3DA5256D ON post_content (image_id)');
        $this->addSql('ALTER TABLE posts ALTER id TYPE UUID');
        $this->addSql('ALTER TABLE posts ALTER id DROP DEFAULT');
        $this->addSql('COMMENT ON COLUMN posts.id IS NULL');
        $this->addSql('ALTER TABLE users DROP name');
        $this->addSql('ALTER TABLE users ALTER email DROP NOT NULL');
        $this->addSql('ALTER TABLE users ALTER password TYPE VARCHAR(100)');
        $this->addSql('ALTER TABLE users ALTER registered_at TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('ALTER TABLE users ALTER registered_at DROP DEFAULT');
        $this->addSql('ALTER TABLE users ALTER registered_at DROP NOT NULL');
        $this->addSql('ALTER TABLE users ALTER hashing_algorithm TYPE VARCHAR(100)');
        $this->addSql('ALTER TABLE users ALTER phone TYPE VARCHAR(15)');
        $this->addSql('COMMENT ON COLUMN users.registered_at IS \'(DC2Type:datetime_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE post_content DROP CONSTRAINT FK_C32D879A3DA5256D');
        $this->addSql('DROP SEQUENCE media_object_id_seq CASCADE');
        $this->addSql('DROP TABLE media_object');
        $this->addSql('ALTER TABLE users ADD name VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE users ALTER email SET NOT NULL');
        $this->addSql('ALTER TABLE users ALTER password TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE users ALTER registered_at TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('ALTER TABLE users ALTER registered_at DROP DEFAULT');
        $this->addSql('ALTER TABLE users ALTER registered_at SET NOT NULL');
        $this->addSql('ALTER TABLE users ALTER hashing_algorithm TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE users ALTER phone TYPE VARCHAR(255)');
        $this->addSql('COMMENT ON COLUMN users.registered_at IS NULL');
        $this->addSql('ALTER TABLE posts ALTER id TYPE UUID');
        $this->addSql('ALTER TABLE posts ALTER id DROP DEFAULT');
        $this->addSql('COMMENT ON COLUMN posts.id IS \'(DC2Type:uuid)\'');
        $this->addSql('DROP INDEX IDX_C32D879A3DA5256D');
        $this->addSql('ALTER TABLE post_content DROP image_id');
        $this->addSql('ALTER TABLE post_content ALTER id TYPE UUID');
        $this->addSql('ALTER TABLE post_content ALTER id DROP DEFAULT');
        $this->addSql('ALTER TABLE post_content ALTER post_id TYPE UUID');
        $this->addSql('ALTER TABLE post_content ALTER post_id DROP DEFAULT');
        $this->addSql('COMMENT ON COLUMN post_content.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN post_content.post_id IS \'(DC2Type:uuid)\'');
    }
}
