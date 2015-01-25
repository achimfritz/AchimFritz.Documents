<?php
namespace AchimFritz\Documents\Domain\Repository;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "AchimFritz.Documents".  *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use AchimFritz\Documents\Domain\Model\ImageDocumentList;

/**
 * @Flow\Scope("singleton")
 */
class ImageDocumentListRepository extends DocumentListRepository {

	/**
	 * @return DocumentList
	 */
	protected function getNewDocumentList() {
		return new ImageDocumentList();
	}


}
