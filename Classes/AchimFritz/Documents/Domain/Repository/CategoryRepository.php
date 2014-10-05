<?php
namespace AchimFritz\Documents\Domain\Repository;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "AchimFritz.Documents".  *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;

/**
 * A repository for Categories
 *
 * @Flow\Scope("singleton")
 */
class CategoryRepository extends \TYPO3\Flow\Persistence\Repository {

	/**
	 * @var \AchimFritz\Documents\Domain\Solr\Repository
	 * @Flow\Inject
	 */
	protected $solrRepository;

	/**
	 * @var \AchimFritz\Documents\Domain\Mongo\Repository
	 * @Flow\Inject
	 */
	protected $mongoRepository;

	/**
	 * findByPath 
	 * 
	 * @param string $path 
	 * @return \TYPO3\Flow\Persistence\QueryResultInterface
	 */
	public function findByPath($path) {
		$query = $this->createQuery();
		return $query->matching(
			$query->like('path', $path . '/%', FALSE)
		)->execute();
	}

	/**
	 * Adds an object to this repository.
	 *
	 * @param object $object The object to add
	 * @return void
	 * @throws \TYPO3\Flow\Persistence\Exception\IllegalObjectTypeException
	 * @throws \SolrClientException
	 * @api
	 */
	public function add($object) {
		$documents = $object->getDocuments();
		foreach ($documents AS $document) {
			$document->addCategory($object);
		}
		parent::add($object);
		$this->solrRepository->updateCategory($object);
	}

	/**
	 * Removes an object from this repository.
	 *
	 * @param object $object The object to remove
	 * @return void
	 * @throws \TYPO3\Flow\Persistence\Exception\IllegalObjectTypeException
	 * @throws \SolrClientException
	 * @api
	 */
	public function remove($object) {
		$documents = $object->getDocuments();
		foreach ($documents AS $document) {
			$document->removeCategory($object);
			$this->solrRepository->update($document);
		}
		parent::remove($object);
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
		$this->solrRepository->updateCategory($object);
	}

	// add customized methods here

}
?>
