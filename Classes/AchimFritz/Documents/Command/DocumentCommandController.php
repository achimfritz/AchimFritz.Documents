<?php
namespace AchimFritz\Documents\Command;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "De.AchimFritz.Intranet".*
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use AchimFritz\Documents\Domain\Model\Document;
use AchimFritz\Documents\Domain\Model\Category;
use AchimFritz\ServiceDescription\ServiceDescriptionInterface;

/**
 * Standard controller for the De.AchimFritz.Intranet package 
 *
 * @Flow\Scope("singleton")
 */
class DocumentCommandController extends \TYPO3\Flow\Cli\CommandController {

	/**
	 * @var \AchimFritz\Documents\Domain\Repository\DocumentRepository
	 * @Flow\Inject
	 */
	protected $documentRepository;

	/**
	 * @var \AchimFritz\Documents\Domain\Solr\Repository
	 * @Flow\Inject
	 */
	protected $solrRepository;

	/**
	 * @var \AchimFritz\Documents\Persistence\DocumentsPersistenceManager
	 * @Flow\Inject
	 */
	protected $documentsPersistenceManager;

	/**
	 * @var string
	 */
	protected $solrQueryString = '*:*';

	/**
	 * cleanSolrCommand 
	 * 
	 * @access public
	 * @return void
	 */
	public function cleanSolrCommand() {
		$this->solrRepository->removeByQueryString($this->solrQueryString);
	}
		
	/**
	 * updateSolrCommand 
	 * 
	 * @return void
	 */
	public function updateSolrCommand() {
		$this->solrRepository->removeAll();
		$documents = $this->documentRepository->findAll();
		foreach ($documents AS $document) {
			$this->solrRepository->update($document);
		}
		$this->documentsPersistenceManager->persistAll();
	}

}

?>
