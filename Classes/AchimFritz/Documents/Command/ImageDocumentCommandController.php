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
	 * @var \AchimFritz\Documents\Domain\Factory\IntegrityFactory
	 * @Flow\Inject
	 */
	protected $integrityFactory;

	/**
	 * @var \AchimFritz\Documents\Persistence\DocumentsPersistenceManager
	 * @Flow\Inject
	 */
	protected $documentPersistenceManager;

	/**
	 * @param string $directoryName 
	 * @return void
	 */
	public function listCommand($directoryName) {
		$documents = $this->documentRepository->findByHead($directoryName);
		foreach ($documents AS $document) {
			$this->outputLine($document->getName());
		}
	}

	/**
	 * @param boolean $confiremed
	 * @return void
	 */
	public function deleteAllCommand($confirmed = FALSE) {
		$documents = $this->documentRepository->findAll();
		$cnt = count($documents);
		if ($confirmed === TRUE) {
			foreach ($documents AS $document) {
				$this->documentRepository->remove($document);
			}
			try {
				$this->documentPersistenceManager->persistAll();
				$this->outputLine('SUCCESS: delete ' . $cnt . ' documents');
			} catch (\AchimFritz\Documents\Persistence\Exception $e) {
				$this->outputLine('ERROR: ' . $e->getMessage());
			}
		} else {
			$this->outputLine('WARNING: will realy delete ' . $cnt . ' documents from persistence layer');
		}
	}


	/**
	 * @param string $directoryName 
	 * @return void
	 */
	public function deleteCommand($directoryName) {
		$documents = $this->documentRepository->findByHead($directoryName);
		$cnt = count($documents);
		foreach ($documents AS $document) {
			$this->documentRepository->remove($document);
		}
		try {
			$this->documentPersistenceManager->persistAll();
			$this->outputLine('SUCCESS: delete ' . $cnt . ' documents');
		} catch (\AchimFritz\Documents\Persistence\Exception $e) {
			$this->outputLine('ERROR: ' . $e->getMessage());
		}
	}

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

	/**
	 * @return void
	 */
	public function integrityCommand() {
		try {
			$integrities = $this->integrityFactory->createImageIntegrities();
			foreach ($integrities AS $integrity) {
				if ($integrity->getCountDiffers() === TRUE) {
					$this->outputLine($integrity->getName() . ' ' . $integrity->getCountFileSystem() . ' ' . $integrity->getCountSolr());
				}
			}
		} catch (\AchimFritz\Documents\Domain\Factory\Exception $e) {
			$this->outputLine('ERROR: ' . $e->getMessage() . ' - ' . $e->getCode());
		}
	}
		
}
