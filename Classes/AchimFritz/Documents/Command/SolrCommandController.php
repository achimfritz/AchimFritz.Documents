<?php
namespace AchimFritz\Documents\Command;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "De.AchimFritz.Intranet".*
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use AchimFritz\Documents\Domain\Model\Document;
use AchimFritz\Documents\Domain\Model\Category;

/**
 * @Flow\Scope("singleton")
 */
class SolrCommandController extends \TYPO3\Flow\Cli\CommandController {

	/**
	 * @var \AchimFritz\Documents\Domain\Repository\DocumentRepository
	 * @Flow\Inject
	 */
	protected $documentRepository;

	/**
	 * @var \AchimFritz\Documents\Solr\ClientWrapper
	 * @Flow\Inject
	 */
	protected $solrClientWrapper;

	/**
	 * @var \AchimFritz\Documents\Solr\InputDocumentFactory
	 * @Flow\Inject
	 */
	protected $solrInputDocumentFactory;


	/**
	 * cleanSolrCommand 
	 * 
	 * @access public
	 * @return void
	 */
	public function emptyCommand() {
		try {
			$this->solrClientWrapper->deleteByQuery('*:*');
			$this->solrClientWrapper->commit();
			$this->outputLine('SUCCESS: delete all documents');
		} catch (\SolrException $e) {
			$this->outputLine('ERROR: ' . $e->getMessage() . ' - ' . $e->getCode());
		}
	}
		
	/**
	 * updateSolrCommand 
	 * 
	 * @return void
	 */
	public function updateCommand() {
		$documents = $this->documentRepository->findAll();
		$solrInputDocuments = array();
		foreach ($documents AS $document) {
			$solrInputDocument = $this->solrInputDocumentFactory->create($document);
			$solrInputDocuments[] = $solrInputDocument;
		}
		try {
			$this->solrClientWrapper->addDocuments($solrInputDocuments);
			$this->solrClientWrapper->commit();
			$this->outputLine('SUCCESS: update ' . count($documents) . ' documents'); 
		} catch (\SolrException $e) {
			$this->outputLine('ERROR: ' . $e->getMessage() . ' - ' . $e->getCode());
		}
	}

}

?>
