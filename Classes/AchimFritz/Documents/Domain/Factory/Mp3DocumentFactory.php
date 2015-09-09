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
	 * @var \AchimFritz\Documents\Domain\Policy\Mp3DocumentPolicy
	 */
	protected $policy;

	/**
	 * @param string $name
	 * @param string $mountPoint
	 * @return \AchimFritz\Documents\Domain\Model\FileSystemDocument
	 */
	public function create($name, $mountPoint = '') {
		$document = parent::create($name, $mountPoint);
		if ($this->policy->isValid($document) === FALSE) {
			throw new Exception('invalide document', 1441819439);
		}
	}

	/**
	 * @return \AchimFritz\Documents\Domain\Model\Mp3Document
	 */
	protected function getDocument() {
		return new Mp3Document();
	}


}
?>
