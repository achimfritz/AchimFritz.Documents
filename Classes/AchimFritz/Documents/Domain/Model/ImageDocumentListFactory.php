<?php
namespace AchimFritz\Documents\Domain\Model;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "AchimFritz.FileSystemDocuments".*
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;

/**
 * @Flow\Scope("singleton")
 */
class ImageDocumentListFactory extends FileSystemDocumentListFactory {

	/**
	 * @var \AchimFritz\Documents\Domain\Repository\ImageDocumentRepository
	 * @Flow\Inject
	 */
	protected $documentRepository;

	/**
	 * @var \AchimFritz\Documents\Configuration\ImageDocumentConfiguration
	 * @Flow\Inject
	 */
	protected $mp3Configuration;

	/**
	 * @var string
	 */
	protected $extension = 'jpg';


	/**
	 * @return \AchimFritz\Documents\Configuration\FileSystemDocumentConfiguration
	 */
	protected function getConfiguration() {
		return $this->mp3Configuration;
	}

	/**
	 * @return \AchimFritz\Documents\Domain\Model\ImageDocumentList
	 */
	protected function getDocumentList() {
		return new ImageDocumentList();
	}


}
?>
