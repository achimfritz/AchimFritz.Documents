<?php
namespace AchimFritz\Documents\Domain\Factory;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "AchimFritz.FileSystemDocuments".*
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use AchimFritz\Documents\Domain\Model\Mp3Document;

/**
 * @Flow\Scope("singleton")
 */
class Mp3DocumentFactory extends FileSystemDocumentFactory {

	/**
	 * @return \AchimFritz\Documents\Domain\Model\Mp3Document
	 */
	protected function getDocument() {
		return new Mp3Document();
	}


}
?>
