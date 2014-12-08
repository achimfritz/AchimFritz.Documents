<?php
namespace AchimFritz\Documents\Domain\Repository;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "AchimFritz.Documents".  *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Persistence\Repository;
use AchimFritz\Documents\Domain\Model\Category;

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
			throw new Exception('category has documents ' . $category->getPath(), 1417447460);
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
		$this->updateDocuments($object);
		$childs = $this->findChilds($object);
		foreach ($childs AS $category) {
			$this->updateDocuments($category);
		}
		/*
		$documents = $this->documentRepository->findByCategory($object);
		$solrInputDocuments = array();
		foreach ($documents AS $document) {
			$solrInputDocument = $this->solrInputDocumentFactory->create($document);
			$solrInputDocuments[] = $solrInputDocument;
		}
		if (count($solrInputDocuments) > 0) {
			$this->solrClientWrapper->addDocuments($solrInputDocuments);
		}
		*/
	}

	/**
	 * @param Category $category 
	 * @return void
	 */
	protected function updateDocuments(Category $category) {
		$documents = $this->documentRepository->findByCategory($category);
		$solrInputDocuments = array();
		foreach ($documents AS $document) {
			$solrInputDocument = $this->solrInputDocumentFactory->create($document);
			$solrInputDocuments[] = $solrInputDocument;
		}
		if (count($solrInputDocuments) > 0) {
			$this->solrClientWrapper->addDocuments($solrInputDocuments);
		}
	}

	/**
	 * @param Category $category 
	 * @return \TYPO3\FLOW3\Persistence\QueryResultInterface
	 */
	public function findChilds(Category $category) {
		$path = $category->getPath();
		$query = $this->createQuery();
		return $query->matching(
			$query->like('path', $path. '/%', FALSE)
		)->execute();
	}


	/**
	 * @param mixed $object 
	 * @return void
	 */
	protected function parentRemove($object) {
		return parent::remove($object);
	}


}
