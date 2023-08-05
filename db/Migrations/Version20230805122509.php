<?php

declare(strict_types=1);

namespace Database\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230805122509 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE permissions_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE roles_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE users_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE permissions (id INT NOT NULL, parent_id INT DEFAULT NULL, name VARCHAR(32) NOT NULL, value VARCHAR(32) NOT NULL, description VARCHAR(32) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_2DEDCC6F5E237E06 ON permissions (name)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_2DEDCC6F1D775834 ON permissions (value)');
        $this->addSql('CREATE INDEX IDX_2DEDCC6F727ACA70 ON permissions (parent_id)');
        $this->addSql('CREATE TABLE roles (id INT NOT NULL, parent_id INT DEFAULT NULL, name VARCHAR(32) NOT NULL, key VARCHAR(32) NOT NULL, class VARCHAR(32) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_B63E2EC75E237E06 ON roles (name)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_B63E2EC78A90ABA9 ON roles (key)');
        $this->addSql('CREATE INDEX IDX_B63E2EC7727ACA70 ON roles (parent_id)');
        $this->addSql('CREATE TABLE roles_permissions (role_id INT NOT NULL, permission_id INT NOT NULL, PRIMARY KEY(role_id, permission_id))');
        $this->addSql('CREATE INDEX IDX_CEC2E043D60322AC ON roles_permissions (role_id)');
        $this->addSql('CREATE INDEX IDX_CEC2E043FED90CCA ON roles_permissions (permission_id)');
        $this->addSql('CREATE TABLE users (id INT NOT NULL, role_id INT DEFAULT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, firstname VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, gender INT NOT NULL, state INT NOT NULL, last_logged_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1483A5E9E7927C74 ON users (email)');
        $this->addSql('CREATE INDEX IDX_1483A5E9D60322AC ON users (role_id)');
        $this->addSql('COMMENT ON COLUMN users.last_logged_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN users.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN users.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE permissions ADD CONSTRAINT FK_2DEDCC6F727ACA70 FOREIGN KEY (parent_id) REFERENCES permissions (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE roles ADD CONSTRAINT FK_B63E2EC7727ACA70 FOREIGN KEY (parent_id) REFERENCES roles (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE roles_permissions ADD CONSTRAINT FK_CEC2E043D60322AC FOREIGN KEY (role_id) REFERENCES roles (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE roles_permissions ADD CONSTRAINT FK_CEC2E043FED90CCA FOREIGN KEY (permission_id) REFERENCES permissions (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE users ADD CONSTRAINT FK_1483A5E9D60322AC FOREIGN KEY (role_id) REFERENCES roles (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE permissions_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE roles_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE users_id_seq CASCADE');
        $this->addSql('ALTER TABLE permissions DROP CONSTRAINT FK_2DEDCC6F727ACA70');
        $this->addSql('ALTER TABLE roles DROP CONSTRAINT FK_B63E2EC7727ACA70');
        $this->addSql('ALTER TABLE roles_permissions DROP CONSTRAINT FK_CEC2E043D60322AC');
        $this->addSql('ALTER TABLE roles_permissions DROP CONSTRAINT FK_CEC2E043FED90CCA');
        $this->addSql('ALTER TABLE users DROP CONSTRAINT FK_1483A5E9D60322AC');
        $this->addSql('DROP TABLE permissions');
        $this->addSql('DROP TABLE roles');
        $this->addSql('DROP TABLE roles_permissions');
        $this->addSql('DROP TABLE users');
    }
}
