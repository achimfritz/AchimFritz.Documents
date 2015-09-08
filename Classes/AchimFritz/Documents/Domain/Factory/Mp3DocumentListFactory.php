<?php
namespace AchimFritz\Documents\Domain\Factory;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "AchimFritz.FileSystemDocuments".*
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;

/**
 * @Flow\Scope("singleton")
 */
class Mp3DocumentListFactory extends FileSystemDocumentListFactory {

	/**
	 * @var \AchimFritz\Documents\Domain\Repository\Mp3DocumentRepository
	 * @Flow\Inject
	 */
	protected $documentRepository;

	/**
	 * @var \AchimFritz\Documents\Configuration\Mp3DocumentConfiguration
	 * @Flow\Inject
	 */
	protected $mp3Configuration;

	/**
	 * @var string
	 */
	protected $extension = 'mp3';

	/**
	 * @return \AchimFritz\Documents\Configuration\FileSystemDocumentConfiguration
	 */
	protected function getConfiguration() {
		return $this->mp3Configuration;
	}

	/**
	 * @return \AchimFritz\Documents\Domain\Model\Mp3DocumentList
	 */
	protected function getDocumentList() {
		return new Mp3DocumentList();
	}


}
?>
