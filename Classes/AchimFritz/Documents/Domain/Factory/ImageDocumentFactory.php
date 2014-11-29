<?php
namespace AchimFritz\Documents\Domain\Factory;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "AchimFritz.FileSystemDocuments".*
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use AchimFritz\Documents\Domain\Model\ImageDocument;

/**
 * @Flow\Scope("singleton")
 */
class ImageDocumentFactory extends FileSystemDocumentFactory {

	/**
	 * @return \AchimFritz\Documents\Domain\Model\ImageDocument
	 */
	protected function getDocument() {
		return new ImageDocument();
	}


}
?>
