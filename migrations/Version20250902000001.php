<?php

declare(strict_types=1);

namespace Database\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Initial database schema
 */
final class Version20250902000001 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create initial database schema';
    }

    public function up(Schema $schema): void
    {
        // Create users table
        $this->addSql('CREATE TABLE users (
            id UUID NOT NULL DEFAULT gen_random_uuid(),
            email VARCHAR(255) NOT NULL,
            password VARCHAR(255) NOT NULL,
            name VARCHAR(255) NOT NULL,
            role VARCHAR(50) NOT NULL DEFAULT \'user\',
            created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY(id)
        )');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_USERS_EMAIL ON users (email)');

        // Create payments table
        $this->addSql('CREATE TABLE payments (
            id UUID NOT NULL DEFAULT gen_random_uuid(),
            user_id UUID NOT NULL,
            amount DECIMAL(10, 2) NOT NULL,
            currency VARCHAR(3) NOT NULL DEFAULT \'CZK\',
            bank_account VARCHAR(255) NOT NULL,
            variable_symbol VARCHAR(20) DEFAULT NULL,
            due_date DATE NOT NULL,
            status VARCHAR(50) NOT NULL DEFAULT \'pending\',
            qr_code TEXT DEFAULT NULL,
            created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY(id)
        )');
        $this->addSql('CREATE INDEX IDX_PAYMENTS_USER ON payments (user_id)');
        $this->addSql('CREATE INDEX IDX_PAYMENTS_STATUS ON payments (status)');
        $this->addSql('ALTER TABLE payments ADD CONSTRAINT FK_PAYMENTS_USER FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');

        // Create tasks table
        $this->addSql('CREATE TABLE tasks (
            id UUID NOT NULL DEFAULT gen_random_uuid(),
            user_id UUID NOT NULL,
            title VARCHAR(500) NOT NULL,
            description TEXT DEFAULT NULL,
            priority VARCHAR(20) NOT NULL DEFAULT \'medium\',
            status VARCHAR(50) NOT NULL DEFAULT \'todo\',
            due_date TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL,
            completed_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL,
            gitlab_issue_id VARCHAR(100) DEFAULT NULL,
            slack_message_id VARCHAR(100) DEFAULT NULL,
            created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY(id)
        )');
        $this->addSql('CREATE INDEX IDX_TASKS_USER ON tasks (user_id)');
        $this->addSql('CREATE INDEX IDX_TASKS_STATUS ON tasks (status)');
        $this->addSql('CREATE INDEX IDX_TASKS_PRIORITY ON tasks (priority)');
        $this->addSql('ALTER TABLE tasks ADD CONSTRAINT FK_TASKS_USER FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');

        // Create emails table
        $this->addSql('CREATE TABLE emails (
            id UUID NOT NULL DEFAULT gen_random_uuid(),
            user_id UUID DEFAULT NULL,
            message_id VARCHAR(500) NOT NULL,
            from_address VARCHAR(500) NOT NULL,
            to_address VARCHAR(500) NOT NULL,
            subject VARCHAR(1000) NOT NULL,
            body TEXT NOT NULL,
            html_body TEXT DEFAULT NULL,
            category VARCHAR(100) DEFAULT NULL,
            processed BOOLEAN NOT NULL DEFAULT FALSE,
            metadata JSONB DEFAULT NULL,
            received_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL,
            created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY(id)
        )');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_EMAILS_MESSAGE_ID ON emails (message_id)');
        $this->addSql('CREATE INDEX IDX_EMAILS_USER ON emails (user_id)');
        $this->addSql('CREATE INDEX IDX_EMAILS_CATEGORY ON emails (category)');
        $this->addSql('CREATE INDEX IDX_EMAILS_PROCESSED ON emails (processed)');
        $this->addSql('ALTER TABLE emails ADD CONSTRAINT FK_EMAILS_USER FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE');

        // Create notifications table
        $this->addSql('CREATE TABLE notifications (
            id UUID NOT NULL DEFAULT gen_random_uuid(),
            user_id UUID NOT NULL,
            type VARCHAR(100) NOT NULL,
            title VARCHAR(500) NOT NULL,
            message TEXT NOT NULL,
            priority VARCHAR(20) NOT NULL DEFAULT \'normal\',
            read BOOLEAN NOT NULL DEFAULT FALSE,
            data JSONB DEFAULT NULL,
            created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY(id)
        )');
        $this->addSql('CREATE INDEX IDX_NOTIFICATIONS_USER ON notifications (user_id)');
        $this->addSql('CREATE INDEX IDX_NOTIFICATIONS_READ ON notifications (read)');
        $this->addSql('CREATE INDEX IDX_NOTIFICATIONS_TYPE ON notifications (type)');
        $this->addSql('ALTER TABLE notifications ADD CONSTRAINT FK_NOTIFICATIONS_USER FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE IF EXISTS notifications');
        $this->addSql('DROP TABLE IF EXISTS emails');
        $this->addSql('DROP TABLE IF EXISTS tasks');
        $this->addSql('DROP TABLE IF EXISTS payments');
        $this->addSql('DROP TABLE IF EXISTS users');
    }
}