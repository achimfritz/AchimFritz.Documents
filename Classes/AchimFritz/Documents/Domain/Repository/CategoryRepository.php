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
	 * @param object $object The object to remove
	 * @return void
	 * @throws \TYPO3\Flow\Persistence\Exception\IllegalObjectTypeException
	 * @api
	 */
	public function remove($object) {
		$category = $object;
		$documents = $this->documentRepository->findByCategory($category);
		foreach ($documents AS $document) {
			$document->removeCategory($category);
			$this->documentRepository->update($document);
		}
		return $this->parentRemove($category);
	}

	/**
	 * @param mixed $object 
	 * @return void
	 */
	protected function parentRemove($object) {
		return parent::remove($object);
	}


}
