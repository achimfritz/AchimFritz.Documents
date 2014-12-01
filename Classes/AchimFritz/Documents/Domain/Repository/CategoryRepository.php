<?php
namespace AchimFritz\Documents\Domain\Repository;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "AchimFritz.Documents".  *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Persistence\Repository;

/**
 * @Flow\Scope("singleton")
 */
class CategoryRepository extends Repository {

	/**
	 * @Flow\Inject
	 * @var \AchimFritz\Documents\Domain\Repository\DocumentRepository
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
	 * @param object $object The object to remove
	 * @return void
	 * @throws \TYPO3\Flow\Persistence\Exception\IllegalObjectTypeException
	 * @api
	 */
	public function remove($object) {
		$category = $object;
		$documents = $this->documentRepository->findByCategory($category);
		if (count($documents) > 0) {
			throw new Exception('category has documents', 1417447460);
		}
		return $this->parentRemove($category);
	}

	/**
	 * Schedules a modified object for persistence.
	 *
	 * @param object $object The modified object
	 * @throws \TYPO3\Flow\Persistence\Exception\IllegalObjectTypeException
	 * @throws \SolrClientException
	 * @api
	 */
	public function update($object) {
		parent::update($object);
		$documents = $this->documentRepository->findByByCategory($object);
		$solrInputDocuments = array();
		foreach ($documents AS $document) {
			$solrInputDocument = $this->solrInputDocumentFactory->create($object);
			$solrInputDocuments[] = $solrInputDocument;
		}
		$this->solrClientWrapper->addDocuments($solrInputDocuments);
	}


	/**
	 * @param mixed $object 
	 * @return void
	 */
	protected function parentRemove($object) {
		return parent::remove($object);
	}


}
