<?php
namespace AchimFritz\Documents\Domain\Policy;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "AchimFritz.Documents".  *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use AchimFritz\Documents\Domain\Model\Mp3Document as Document;


/**
 * @Flow\Scope("singleton")
 */
class Mp3DocumentPolicy {

	const PATH_DEPTH = 4;

	/**
	 * @param Mp3Document $document
	 * @return boolean
	 */
	public function isValid(Document $document) {
		if (count($document->getPathArrPlain()) !== self::PATH_DEPTH) {
			return FALSE;
		} else {
			return TRUE;
		}
		return TRUE;
	}

}
