<?php
namespace AchimFritz\Documents\Command;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "De.AchimFritz.Intranet".*
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use AchimFritz\Documents\Domain\Model\Document;
use AchimFritz\Documents\Domain\Service\PathService;
use AchimFritz\Documents\Domain\FileSystemInterface;

/**
 * @Flow\Scope("singleton")
 */
class ImageDocumentCommandController extends \TYPO3\Flow\Cli\CommandController {

	/**
	 * @var \AchimFritz\Documents\Domain\Repository\ImageDocumentRepository
	 * @Flow\Inject
	 */
	protected $documentRepository;

	/**
	 * @var \AchimFritz\Documents\Domain\Factory\ImageDocumentFactory
	 * @Flow\Inject
	 */
	protected $documentFactory;

	/**
	 * @var \AchimFritz\Documents\Persistence\DocumentsPersistenceManager
	 * @Flow\Inject
	 */
	protected $documentPersistenceManager;

	/**
	 * @param string $directoryName
	 * @return void
	 */
	public function indexCommand($directoryName) {
		$path = FileSystemInterface::IMAGE_MOUNT_POINT . PathService::PATH_DELIMITER . $directoryName;
		try {
			$directoryIterator = new \DirectoryIterator($path);
		} catch (\Exception $e) {
			$this->outputLine('ERROR: ' . $e->getMessage());
			$this->quit();
		}
		$cnt = 0;
		foreach ($directoryIterator AS $fileInfo) {
			if ($fileInfo->getExtension() === 'jpg') {
				$cnt++;
				$document = $this->documentFactory->create($directoryName . PathService::PATH_DELIMITER . $fileInfo->getBasename());
				$this->documentRepository->add($document);
			}
		}
		try {
			$this->documentPersistenceManager->persistAll();
			$this->outputLine('SUCCESS: insert ' . $cnt . ' documents');
		} catch (\AchimFritz\Documents\Persistence\Exception $e) {
			$this->outputLine('ERROR: ' . $e->getMessage());
		}
	}
		
}

?>
