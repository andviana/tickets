<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230916172512 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE lote_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE produto_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE ticket_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE evento (id UUID NOT NULL, nome VARCHAR(255) NOT NULL, data TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, local VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN evento.id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE lote (id INT NOT NULL, produto_id INT NOT NULL, quantidade INT NOT NULL, data_emissao TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_65B4329F105CFD56 ON lote (produto_id)');
        $this->addSql('CREATE TABLE pessoa (id UUID NOT NULL, nome VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, cpf VARCHAR(11) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN pessoa.id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE produto (id INT NOT NULL, denominacao VARCHAR(255) NOT NULL, valor NUMERIC(10, 2) NOT NULL, ativo BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE registro (id UUID NOT NULL, ticket_id UUID NOT NULL, user_registro_id UUID NOT NULL, data_registro TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_397CA85B700047D2 ON registro (ticket_id)');
        $this->addSql('CREATE INDEX IDX_397CA85B90DEC63B ON registro (user_registro_id)');
        $this->addSql('COMMENT ON COLUMN registro.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN registro.ticket_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN registro.user_registro_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE ticket (id UUID NOT NULL, produto_id INT NOT NULL, evento_id UUID NOT NULL, user_emissao_id UUID NOT NULL, lote_id INT DEFAULT NULL, numero INT NOT NULL, data_emissao TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, recolhido BOOLEAN NOT NULL, relation INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_97A0ADA3F55AE19E ON ticket (numero)');
        $this->addSql('CREATE INDEX IDX_97A0ADA3105CFD56 ON ticket (produto_id)');
        $this->addSql('CREATE INDEX IDX_97A0ADA387A5F842 ON ticket (evento_id)');
        $this->addSql('CREATE INDEX IDX_97A0ADA31F8AAC9E ON ticket (user_emissao_id)');
        $this->addSql('CREATE INDEX IDX_97A0ADA3B172197C ON ticket (lote_id)');
        $this->addSql('COMMENT ON COLUMN ticket.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN ticket.evento_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN ticket.user_emissao_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE "user" (id UUID NOT NULL, pessoa_id UUID NOT NULL, username VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649F85E0677 ON "user" (username)');
        $this->addSql('CREATE INDEX IDX_8D93D649DF6FA0A5 ON "user" (pessoa_id)');
        $this->addSql('COMMENT ON COLUMN "user".id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN "user".pessoa_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE messenger_messages (id BIGSERIAL NOT NULL, body TEXT NOT NULL, headers TEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, available_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, delivered_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_75EA56E0FB7336F0 ON messenger_messages (queue_name)');
        $this->addSql('CREATE INDEX IDX_75EA56E0E3BD61CE ON messenger_messages (available_at)');
        $this->addSql('CREATE INDEX IDX_75EA56E016BA31DB ON messenger_messages (delivered_at)');
        $this->addSql('COMMENT ON COLUMN messenger_messages.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN messenger_messages.available_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN messenger_messages.delivered_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE OR REPLACE FUNCTION notify_messenger_messages() RETURNS TRIGGER AS $$
            BEGIN
                PERFORM pg_notify(\'messenger_messages\', NEW.queue_name::text);
                RETURN NEW;
            END;
        $$ LANGUAGE plpgsql;');
        $this->addSql('DROP TRIGGER IF EXISTS notify_trigger ON messenger_messages;');
        $this->addSql('CREATE TRIGGER notify_trigger AFTER INSERT OR UPDATE ON messenger_messages FOR EACH ROW EXECUTE PROCEDURE notify_messenger_messages();');
        $this->addSql('ALTER TABLE lote ADD CONSTRAINT FK_65B4329F105CFD56 FOREIGN KEY (produto_id) REFERENCES produto (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE registro ADD CONSTRAINT FK_397CA85B700047D2 FOREIGN KEY (ticket_id) REFERENCES ticket (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE registro ADD CONSTRAINT FK_397CA85B90DEC63B FOREIGN KEY (user_registro_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE ticket ADD CONSTRAINT FK_97A0ADA3105CFD56 FOREIGN KEY (produto_id) REFERENCES produto (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE ticket ADD CONSTRAINT FK_97A0ADA387A5F842 FOREIGN KEY (evento_id) REFERENCES evento (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE ticket ADD CONSTRAINT FK_97A0ADA31F8AAC9E FOREIGN KEY (user_emissao_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE ticket ADD CONSTRAINT FK_97A0ADA3B172197C FOREIGN KEY (lote_id) REFERENCES lote (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "user" ADD CONSTRAINT FK_8D93D649DF6FA0A5 FOREIGN KEY (pessoa_id) REFERENCES pessoa (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE lote_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE produto_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE ticket_id_seq CASCADE');
        $this->addSql('ALTER TABLE lote DROP CONSTRAINT FK_65B4329F105CFD56');
        $this->addSql('ALTER TABLE registro DROP CONSTRAINT FK_397CA85B700047D2');
        $this->addSql('ALTER TABLE registro DROP CONSTRAINT FK_397CA85B90DEC63B');
        $this->addSql('ALTER TABLE ticket DROP CONSTRAINT FK_97A0ADA3105CFD56');
        $this->addSql('ALTER TABLE ticket DROP CONSTRAINT FK_97A0ADA387A5F842');
        $this->addSql('ALTER TABLE ticket DROP CONSTRAINT FK_97A0ADA31F8AAC9E');
        $this->addSql('ALTER TABLE ticket DROP CONSTRAINT FK_97A0ADA3B172197C');
        $this->addSql('ALTER TABLE "user" DROP CONSTRAINT FK_8D93D649DF6FA0A5');
        $this->addSql('DROP TABLE evento');
        $this->addSql('DROP TABLE lote');
        $this->addSql('DROP TABLE pessoa');
        $this->addSql('DROP TABLE produto');
        $this->addSql('DROP TABLE registro');
        $this->addSql('DROP TABLE ticket');
        $this->addSql('DROP TABLE "user"');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
