<?php
namespace AchimFritz\Documents\Command;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "De.AchimFritz.Intranet".*
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use AchimFritz\Documents\Domain\Model\Document;
use AchimFritz\Documents\Domain\Service\PathService;

/**
 * @Flow\Scope("singleton")
 */
abstract class AbstractFileSystemDocumentCommandController extends \TYPO3\Flow\Cli\CommandController {

	/**
	 * @var \AchimFritz\Documents\Domain\Service\FileSystem\RenameService
	 * @Flow\Inject
	 */
	protected $renameService;

	/**
	 * @var \AchimFritz\Documents\Persistence\DocumentsPersistenceManager
	 * @Flow\Inject
	 */
	protected $documentPersistenceManager;

	/**
	 * @return string
	 */
	abstract protected function getMountPoint();

	/**
	 * @return string
	 */
	abstract protected function getExtension();

	/**
	 * @param string $directoryName 
	 * @return void
	 */
	public function listCommand($directoryName) {
		$documents = $this->documentRepository->findByHead($directoryName);
		if (count($documents) === 0) {
			$this->outputLine('WARNING: no documents found');
		}
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
		$path = $this->getMountPoint() . PathService::PATH_DELIMITER . $directoryName;
		try {
			$directoryIterator = new \DirectoryIterator($path);
		} catch (\Exception $e) {
			$this->outputLine('ERROR: ' . $e->getMessage());
			$this->quit();
		}
		$cnt = 0;
		foreach ($directoryIterator AS $fileInfo) {
			if ($fileInfo->getExtension() === $this->getExtension()) {
				$document = $this->documentRepository->findOneByName($directoryName . PathService::PATH_DELIMITER . $fileInfo->getBasename());
				if ($document instanceof Document === TRUE) {
					$this->outputLine('WARNING: document already persisted ... ignorierung');
				} else {
					$cnt++;
					$document = $this->documentFactory->create($directoryName . PathService::PATH_DELIMITER . $fileInfo->getBasename());
					$this->documentRepository->add($document);
				}
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
			$integrities = $this->integrityFactory->createIntegrities();
			foreach ($integrities AS $integrity) {
				if ($integrity->getCountDiffers() === TRUE) {
					$this->outputLine($integrity->getName() . ' ' . $integrity->getCountFileSystem() . ' ' . $integrity->getCountSolr());
				}
			}
		} catch (\AchimFritz\Documents\Domain\Factory\Exception $e) {
			$this->outputLine('ERROR: ' . $e->getMessage() . ' - ' . $e->getCode());
		}
	}

	/**
	 * @param string $directoryName
	 * @return void
	 */
	public function renameFilesCommand($directoryName) {
		$path = $this->getMountPoint() . PathService::PATH_DELIMITER . $directoryName;
		try {
			$directoryIterator = new \DirectoryIterator($path);
		} catch (\Exception $e) {
			$this->outputLine('ERROR: ' . $e->getMessage());
			$this->quit();
		}
		$updates = 0;
		foreach ($directoryIterator AS $fileInfo) {
			if ($fileInfo->isDir() === FALSE) {
				$document = $this->documentRepository->findOneByName($directoryName . PathService::PATH_DELIMITER . $fileInfo->getBasename());
				try {
					$renamed = $this->renameService->rename($fileInfo->getRealPath());
					$this->outputLine('SUCCESS renamed to ' . $renamed);
					if ($document instanceof Document === TRUE) {
						$this->outputLine('WARNING: document already persisted ... updating');
						$updateFileInfo = new \SplFileInfo($renamed);
						$document->setName($directoryName . PathService::PATH_DELIMITER . $updateFileInfo->getBasename());
						$this->documentRepository->update($document);
						$updates++;
					}
				} catch (\AchimFritz\Documents\Domain\Service\FileSystem\Exception $e) {
					$this->outputLine('ERROR: ' . $e->getMessage() . ' - ' . $e->getCode());
				}
			}
		}
		if ($updates > 0) {
			try {
				$this->documentPersistenceManager->persistAll();
				$this->outputLine('SUCCESS: insert ' . $updates . ' documents');
			} catch (\AchimFritz\Documents\Persistence\Exception $e) {
				$this->outputLine('ERROR: ' . $e->getMessage());
			}
		}
	}
		
}
