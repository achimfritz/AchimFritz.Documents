<?php
namespace AchimFritz\Documents\Domain\Repository;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "AchimFritz.Documents".  *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use AchimFritz\Documents\Domain\Model\Category;
use TYPO3\Flow\Persistence\Repository;

/**
 * @Flow\Scope("singleton")
 */
class DocumentRepository extends Repository {

	/**
	 * @param Category $category 
	 * @return \TYPO3\FLOW3\Persistence\QueryResultInterface
	 */
	public function findByCategory(Category $category) {
		$query = $this->createQuery();
		return $query->matching(
			$query->contains('categories', $category)
		)->execute();
	}


}
