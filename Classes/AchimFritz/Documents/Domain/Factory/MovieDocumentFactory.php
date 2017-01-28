<?php
namespace AchimFritz\Documents\Domain\Factory;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "AchimFritz.FileSystemDocuments".*
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use AchimFritz\Documents\Domain\Model\MovieDocument;

/**
 * @Flow\Scope("singleton")
 */
class MovieDocumentFactory extends FileSystemDocumentFactory {

	/**
	 * @var \AchimFritz\Documents\Linux\Command
	 * @Flow\Inject
	 */
	protected $command;

	/**
	 * @param string $name
	 * @param string $mountPoint
	 * @return \AchimFritz\Documents\Domain\Model\MovieDocument
	 */
	public function create($name, $mountPoint = '') {
		$document = parent::create($name, $mountPoint);
		$result = $this->command->movieInfo($document->getAbsolutePath());
		$json = implode("\n", $result);
		$document->setFfmpeg(json_decode($json));
		return $document;
	}

	/**
	 * @return \AchimFritz\Documents\Domain\Model\MovieDocument
	 */
	protected function getDocument() {
		return new MovieDocument();
	}

}
