<?php
namespace AchimFritz\Documents\Domain\Factory;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "AchimFritz.FileSystemDocuments".*
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use AchimFritz\Documents\Domain\Service\PathService;
use AchimFritz\Documents\Domain\Model\FileSystemDocument;

/**
 * @Flow\Scope("singleton")
 */
class FileSystemDocumentFactory {

	/**
	 * @param string $name 
	 * @param string $mountPoint 
	 * @return \AchimFritz\Documents\Domain\Model\FileSystemDocument
	 */
	public function create($name, $mountPoint = '') {
		$document = $this->getDocument();
		$document->setName($name);
		$mDateTime = new \DateTime();
		$absolutePath = $mountPoint . PathService::PATH_DELIMITER . $name;
		$splFileInfo = new \SplFileInfo($absolutePath);
		if ($splFileInfo->isFile() === TRUE) {
			$mDateTime = new \DateTime('@' . $splFileInfo->getMTime());
			$document->setMDateTime($mDateTime);
			$document->setFileHash(sha1_file($splFileInfo->getRealPath()));
		} else {
			throw new Exception('no such file ' . $absolutePath, 1420478777);
		}
		return $document;
	}

	/**
	 * @return \AchimFritz\Documents\Domain\Model\FileSystemDocument
	 */
	protected function getDocument() {
		return new FileSystemDocument();
	}


}
?>
