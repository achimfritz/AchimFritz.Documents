<?php
namespace AchimFritz\Documents\Domain\Repository;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "AchimFritz.Documents".  *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Persistence\Exception\IllegalObjectTypeException;

/**
 * A repository for Documents
 *
 * @Flow\Scope("singleton")
 */
class DocumentRepository extends \TYPO3\Flow\Persistence\Repository {

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
	 * Adds an object to this repository.
	 *
	 * @param object $object The object to add
	 * @return void
	 * @throws \TYPO3\Flow\Persistence\Exception\IllegalObjectTypeException
	 * @throws \SolrClientException
	 * @api
	 */
	public function add($object) {
		parent::add($object);
		$this->solrRepository->update($object);
		$this->mongoRepository->add($object);
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
		$categories = $object->getCategories();
		$this->solrRepository->remove($object);
		$this->mongoRepository->remove($object);
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
		$this->solrRepository->update($object);
		$this->mongoRepository->update($object);
	}

}
?>
