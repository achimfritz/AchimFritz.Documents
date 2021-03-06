<?php
namespace TYPO3\Flow\Persistence\Doctrine\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
	Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your need!
 */
class Version20141005164811 extends AbstractMigration {

	/**
	 * @param Schema $schema
	 * @return void
	 */
	public function up(Schema $schema) {
		// this up() migration is autogenerated, please modify it to your needs
		$this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql");
		
		$this->addSql("CREATE TABLE achimfritz_documents_domain_model_category (persistence_object_identifier VARCHAR(40) NOT NULL, path VARCHAR(255) NOT NULL, dtype VARCHAR(255) NOT NULL, UNIQUE INDEX flow_identity_achimfritz_documents_domain_model_category (path), PRIMARY KEY(persistence_object_identifier)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
		$this->addSql("CREATE TABLE achimfritz_documents_domain_model_category_documents_join (documents_category VARCHAR(40) NOT NULL, documents_document VARCHAR(40) NOT NULL, INDEX IDX_8771F2886FD5975E (documents_category), INDEX IDX_8771F288B1F004E9 (documents_document), PRIMARY KEY(documents_category, documents_document)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
		$this->addSql("CREATE TABLE achimfritz_documents_domain_model_containerdiff (persistence_object_identifier VARCHAR(40) NOT NULL, name VARCHAR(255) NOT NULL, diff INT NOT NULL, countfirst INT NOT NULL, countsecond INT NOT NULL, PRIMARY KEY(persistence_object_identifier)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
		$this->addSql("CREATE TABLE achimfritz_documents_domain_model_document (persistence_object_identifier VARCHAR(40) NOT NULL, name VARCHAR(255) NOT NULL, dtype VARCHAR(255) NOT NULL, UNIQUE INDEX flow_identity_achimfritz_documents_domain_model_document (name), PRIMARY KEY(persistence_object_identifier)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
		$this->addSql("CREATE TABLE achimfritz_documents_domain_solr_facet (persistence_object_identifier VARCHAR(40) NOT NULL, name VARCHAR(255) NOT NULL, countofdocuments INT NOT NULL, PRIMARY KEY(persistence_object_identifier)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
		$this->addSql("CREATE TABLE achimfritz_documents_domain_solr_facetcontainer (persistence_object_identifier VARCHAR(40) NOT NULL, facets VARCHAR(255) NOT NULL, countofdocuments INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(persistence_object_identifier)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
		$this->addSql("ALTER TABLE achimfritz_documents_domain_model_category_documents_join ADD CONSTRAINT FK_8771F2886FD5975E FOREIGN KEY (documents_category) REFERENCES achimfritz_documents_domain_model_category (persistence_object_identifier)");
		$this->addSql("ALTER TABLE achimfritz_documents_domain_model_category_documents_join ADD CONSTRAINT FK_8771F288B1F004E9 FOREIGN KEY (documents_document) REFERENCES achimfritz_documents_domain_model_document (persistence_object_identifier)");
	}

	/**
	 * @param Schema $schema
	 * @return void
	 */
	public function down(Schema $schema) {
		// this down() migration is autogenerated, please modify it to your needs
		$this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql");
		
		$this->addSql("ALTER TABLE achimfritz_documents_domain_model_category_documents_join DROP FOREIGN KEY FK_8771F2886FD5975E");
		$this->addSql("ALTER TABLE achimfritz_documents_domain_model_category_documents_join DROP FOREIGN KEY FK_8771F288B1F004E9");
		$this->addSql("DROP TABLE achimfritz_documents_domain_model_category");
		$this->addSql("DROP TABLE achimfritz_documents_domain_model_category_documents_join");
		$this->addSql("DROP TABLE achimfritz_documents_domain_model_containerdiff");
		$this->addSql("DROP TABLE achimfritz_documents_domain_model_document");
		$this->addSql("DROP TABLE achimfritz_documents_domain_solr_facet");
		$this->addSql("DROP TABLE achimfritz_documents_domain_solr_facetcontainer");
	}
}
