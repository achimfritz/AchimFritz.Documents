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
	 * @return void
	 */
	public function updateCommand() {
		//$documents = $this->documentRepository->findAll();
		$documents = array($this->documentRepository->findAll()->getFirst());
		if (count($documents) === 0) {
			$this->outputLine('WARNING: no documents found');
			$this->quit();
		}
		$solrInputDocuments = array();
		$cnt = 0;
		foreach ($documents AS $document) {
			$solrInputDocument = $this->solrInputDocumentFactory->create($document);
			$solrInputDocuments[] = $solrInputDocument;
			$cnt++;
			//if ($cnt === 1000) {
			if ($cnt === 10) {
				try {
					$this->solrClientWrapper->addDocuments($solrInputDocuments);
					$solrInputDocuments = array();
					//$cnt = 0;
				} catch (\SolrException $e) {
					$this->outputLine('ERROR: ' . $e->getMessage() . ' - ' . $e->getCode());
					$this->quit();
				}
			}
		}
		try {
			$this->solrClientWrapper->addDocuments($solrInputDocuments);
			$this->solrClientWrapper->commit();
			$this->outputLine('SUCCESS: update ' . count($documents) . ' documents'); 
		} catch (\SolrException $e) {
			$this->outputLine('ERROR: ' . $e->getMessage() . ' - ' . $e->getCode());
		}
	}

	/**
	 * @return void
	 */
	public function integrityCommand() {
		$documents = $this->documentRepository->findAll();
		$query = new \SolrQuery();
		$query->setQuery('*:*')->setRows(0)->setStart(0);
		try {
			$queryResponse = $this->solrClientWrapper->query($query);
			$solrCnt = $queryResponse->getResponse()->response->numFound;
			if ($solrCnt === count($documents)) {
				$this->outputLine('SUCCESS: found ' . $solrCnt . ' solr documents and ' . count($documents) . ' persisted documents');
			} else {
				$this->outputLine('WARNIING: found ' . $solrCnt . ' solr documents and ' . count($documents) . ' persisted documents');
			}
		} catch (\SolrException $e) {
			$this->outputLine('ERROR: ' . $e->getMessage() . ' - ' . $e->getCode());
		}
	}

}
