<?php
namespace AchimFritz\Documents\Domain\Repository;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "AchimFritz.Documents".  *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use AchimFritz\Documents\Domain\Model\Mp3DocumentList;

/**
 * @Flow\Scope("singleton")
 */
class Mp3DocumentListRepository extends DocumentListRepository {

	/**
	 * @return DocumentList
	 */
	protected function getNewDocumentList() {
		return new Mp3DocumentList();
	}


}
