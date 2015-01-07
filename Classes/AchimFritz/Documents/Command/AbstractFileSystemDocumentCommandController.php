<?php
namespace AchimFritz\Documents\Command;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "De.AchimFritz.Intranet".*
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use AchimFritz\Documents\Domain\Model\Facet\FileSystemDocument\DocumentExport;
use AchimFritz\Documents\Domain\Model\Category;
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
	 * @var \AchimFritz\Documents\Domain\Service\FileSystem\DirectoryService
	 * @Flow\Inject
	 */
	protected $directoryService;

	/**
	 * @var \AchimFritz\Documents\Persistence\DocumentsPersistenceManager
	 * @Flow\Inject
	 */
	protected $documentPersistenceManager;

	/**
	 * @var array
	 */
	protected $settings;

	/**
	 * @param array $settings 
	 * @return void
	 */
	public function injectSettings($settings) {
		$this->settings = $settings;
	}

	/**
	 * @return string
	 */
	abstract protected function getMountPoint();

	/**
	 * @return string
	 */
	abstract protected function getExtension();

	/**
	 * @param string $directory 
	 * @return void
	 */
	public function listCommand($directory) {
		$documents = $this->documentRepository->findByHead($directory);
		if (count($documents) === 0) {
			$this->outputLine('WARNING: no documents found');
		}
		foreach ($documents AS $document) {
			$this->outputLine($document->getName());
			foreach ($document->getCategories() AS $category) {
				$this->outputLine($category->getPath());
			}
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
	 * @param string $directory 
	 * @return void
	 */
	public function deleteCommand($directory) {
		$documents = $this->documentRepository->findByHead($directory);
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
	 * @param string $directory
	 * @param boolean $update
	 * @return void
	 */
	public function indexCommand($directory, $update = FALSE) {
		try {
			$cnt = $this->indexService->indexDirectory($directory, $update);
			$this->outputLine('SUCCESS: insert ' . $cnt . ' documents');
		} catch (\AchimFritz\Documents\Domain\Service\Exception $e) {
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
					$this->outputLine('WARNING: ' . $integrity->getName() . ' ' . $integrity->getCountFileSystem() . ' ' . $integrity->getCountSolr());
				}
			}
		} catch (\AchimFritz\Documents\Domain\Model\Facet\FileSystemDocument\Exception $e) {
			$this->outputLine('ERROR: ' . $e->getMessage() . ' - ' . $e->getCode());
		}
	}

	/**
	 * @param string $directory
	 * @return void
	 */
	public function renameFilesCommand($directory) {
		$path = $this->getMountPoint() . PathService::PATH_DELIMITER . $directory;
		try {
			$directoryIterator = new \DirectoryIterator($path);
		} catch (\Exception $e) {
			$this->outputLine('ERROR: ' . $e->getMessage());
			$this->quit();
		}
		$updates = 0;
		foreach ($directoryIterator AS $fileInfo) {
			if ($fileInfo->isDir() === FALSE) {
				$document = $this->documentRepository->findOneByName($directory . PathService::PATH_DELIMITER . $fileInfo->getBasename());
				try {
					$renamed = $this->renameService->rename($fileInfo->getRealPath());
					$this->outputLine('SUCCESS renamed to ' . $renamed);
					if ($document instanceof Document === TRUE) {
						$this->outputLine('WARNING: document already persisted ... updating');
						$updateFileInfo = new \SplFileInfo($renamed);
						$document->setName($directory . PathService::PATH_DELIMITER . $updateFileInfo->getBasename());
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

	/**
	 * @param string $name
	 * @param string $paths
	 * @param boolean $useThumb
	 * @param boolean $useFullPath
	 * @return void
	 */
	public function exportCommand($name, $paths, $useThumb = FALSE, $useFullPath = FALSE) {
		$name = trim($name);
		$documents = $this->documentRepository->findByCategoryPaths(explode(',', $paths));
		$documents = $documents->toArray();
		$documents = new \Doctrine\Common\Collections\ArrayCollection($documents);
		$documentExport = new DocumentExport();
		$documentExport->setUseThumb($useThumb);
		$documentExport->setUseFullPath($useFullPath);
		$documentExport->setDocuments($documents);
		$documentExport->setName($name);
		try {
			$cnt = $this->documentExportService->export($documentExport);
			$this->outputLine('SUCCESS: ' . $cnt . ' documents');
		} catch (\AchimFritz\Documents\Domain\Service\FileSystem\Exception $e) {
			$this->outputLine('ERROR: cannot export with ' . $e->getMessage() . ' - ' . $e->getCode());
		}
	}

		
}
