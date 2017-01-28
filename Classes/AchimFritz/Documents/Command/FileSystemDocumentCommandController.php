<?php
namespace AchimFritz\Documents\Command;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "De.AchimFritz.Intranet".*
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use AchimFritz\Documents\Domain\FileSystem\Facet\DocumentExport;
use AchimFritz\Documents\Domain\Model\Category;
use AchimFritz\Documents\Domain\Model\Document;
use AchimFritz\Documents\Domain\Service\PathService;
use AchimFritz\Documents\Domain\Facet\DocumentCollection;

/**
 * @Flow\Scope("singleton")
 */
class FileSystemDocumentCommandController extends \TYPO3\Flow\Cli\CommandController {

	/**
	 * @var \AchimFritz\Documents\Domain\FileSystem\Service\DocumentExportService
	 * @Flow\Inject
	 */
	protected $documentExportService;

	/**
	 * @var \AchimFritz\Documents\Domain\Repository\FileSystemDocumentRepository
	 * @Flow\Inject
	 */
	protected $documentRepository;

	/**
	 * @var \AchimFritz\Documents\Domain\FileSystem\Service\RenameService
	 * @Flow\Inject
	 */
	protected $renameService;

	/**
	 * @var \AchimFritz\Documents\Domain\FileSystem\Service\DirectoryService
	 * @Flow\Inject
	 */
	protected $directoryService;

	/**
	 * @var \AchimFritz\Documents\Persistence\DocumentsPersistenceManager
	 * @Flow\Inject
	 */
	protected $documentPersistenceManager;

	/**
	 * @var \AchimFritz\Documents\Configuration\FileSystemDocumentConfiguration
	 * @Flow\Inject
	 */
	protected $configuration;

	/**
	 * @Flow\Inject
	 * @var \AchimFritz\Documents\Domain\Service\DocumentCollectionService
	 */
	protected $documentCollectionService;

	/**
	 * @var \AchimFritz\Documents\Domain\Factory\FileSystemDocumentFactory
	 * @Flow\Inject
	 */
	protected $documentFactory;

	/**
	 * @var \AchimFritz\Documents\Domain\Repository\CategoryRepository
	 * @Flow\Inject
	 */
	protected $categoryRepository;

	/**
	 * @var \AchimFritz\Documents\Domain\Service\FileSystemDocumentIndexService
	 * @Flow\Inject
	 */
	protected $indexService;

	/**
	 * @var \AchimFritz\Documents\Domain\FileSystem\Factory\IntegrityFactory
	 * @Flow\Inject
	 */
	protected $integrityFactory;


	/**
	 * list --directory=2015_05_05_venedig
	 *
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
	 * @param string $directory
	 * @return void
	 */
	public function saveTstampCommand($directory) {
		$documents = $this->documentRepository->findByHead($directory);
		if (count($documents) === 0) {
			$this->outputLine('WARNING: no documents found');
		}
		foreach ($documents AS $document) {
			$this->outputLine($document->getName() . ' ' . $document->getMDateTimeForFsStat());
		}
	}

	/**
	 * deleteAll --confirmed=FALSE
	 *
	 * @param boolean $confirmed
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
	 * deletedirectory --directory=2015_05_05_venedig
	 *
	 * @param string $directory 
	 * @return void
	 */
	public function deleteDirectoryCommand($directory) {
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
	 * show --name=2006_10_23_roland_scan_roland_gisela/fritzam_digcam_hochzeit_britta_dsci0048.jpg
	 *
	 * @param string $name
	 * @return void
	 */
	public function showCommand($name) {
		$document = $this->documentRepository->findOneByName($name);
		if ($document instanceof Document) {
			$this->outputLine($document->getName() . ' - ' . $document->getFileHash());
			foreach ($document->getCategories() AS $category) {
				$this->outputLine($category->getPath());
			}
		} else {
			$this->outputLine('WARNING: no document found ' . $name);
		}
	}

	/**
	 * showbyhasn --hash=14c621173ede72853376bbbbce5a38c3b40276c5
	 *
	 * @param string $hash
	 * @return void
	 */
	public function showByHashCommand($hash) {
		$document = $this->documentRepository->findOneByFileHash($hash);
		if ($document instanceof Document) {
			$this->outputLine($document->getName() . ' - ' . $document->getFileHash());
			foreach ($document->getCategories() AS $category) {
				$this->outputLine($category->getPath());
			}
		} else {
			$this->outputLine('WARNING: no document found ' . $hash);
		}
	}

	/**
	 * delete --name=2006_10_23_roland_scan_roland_gisela/fritzam_digcam_hochzeit_britta_dsci0048.jpg
	 *
	 * @param string $name
	 * @return void
	 */
	public function deleteCommand($name) {
		$document = $this->documentRepository->findOneByName($name);
		if ($document instanceof Document) {
			$this->documentRepository->remove($document);
			try {
				$this->documentPersistenceManager->persistAll();
				$this->outputLine('SUCCESS: delete ' . $name);
			} catch (\AchimFritz\Documents\Persistence\Exception $e) {
				$this->outputLine('ERROR: ' . $e->getMessage());
			}
		} else {
			$this->outputLine('WARNING: no document found ' . $name);
		}
	}

	/**
	 * index --directory=2015_05_05_venedig --update=FALSE
	 *
	 * @param string $directory
	 * @param boolean $update
	 * @return void
	 */
	public function indexCommand($directory, $update = FALSE) {
		try {
			$cnt = $this->indexService->indexDirectory($directory, $update);
			$this->outputLine('SUCCESS: insert ' . $cnt . ' documents for directory ' . $directory);
		} catch (\AchimFritz\Documents\Exception $e) {
			$this->outputLine('ERROR: ' . $directory . ' - ' . $e->getMessage() . ' - ' . $e->getCode());
		}
	}

	/**
	 * @param string $directory
	 * @return void
	 * @throws \AchimFritz\Documents\Domain\Factory\Exception
	 */
	public function showDirectoryCommand($directory) {
		$path = $this->getMountPoint() . PathService::PATH_DELIMITER . $directory;
		try {
			$fileNames = $this->directoryService->getFileNamesInDirectory($path, $this->getFileExtension());
			foreach ($fileNames AS $fileName) {
				$document = $this->documentFactory->create($directory . PathService::PATH_DELIMITER . $fileName, $this->getMountPoint());
				$this->outputLine($document->getName() . ': ' . $document->getFileHash());
			}
		} catch (\AchimFritz\Documents\Exception $e) {
			$this->outputLine('ERROR: ' . $directory . ' - ' . $e->getMessage() . ' - ' . $e->getCode());
		}
	}


	/**
	 * integrity
	 *
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
		} catch (\AchimFritz\Documents\Domain\FileSystem\Factory\Exception $e) {
			$this->outputLine('ERROR: ' . $e->getMessage() . ' - ' . $e->getCode());
		}
	}

	/**
	 * renameFiles --directory=2015_05_05_venedig
	 *
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
				} catch (\AchimFritz\Documents\Domain\FileSystem\Service\Exception $e) {
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
	 * mergeCommand --name=2015_05_05_venedig/foo.jpg --path=tags/bestof,foo/bar
	 * 
	 * @param string $name 
	 * @param string $path 
	 * @return void
	 */
	public function mergeCommand($name, $path) {
		$documentCollection = $this->createDocumentCollection($name, $path);
		$cnt = $this->documentCollectionService->merge($documentCollection);
		try {
			$this->documentPersistenceManager->persistAll();
			$this->outputLine('SUCCESS: merged');
		} catch (\AchimFritz\Documents\Persistence\Exception $e) {
			$this->outputLine('ERROR: cannot merge with ' . $e->getMessage() . ' - ' . $e->getCode());
		}
	}

	/**
	 * @param string $name 
	 * @param string $path 
	 * @return \AchimFritz\Documents\Domain\Facet\DocumentCollection
	 */
	protected function createDocumentCollection($name, $path) {
		$document = $this->documentRepository->findOneByName(trim($name));
		if ($document instanceof Document === FALSE) {
			$this->outputLine('document with ' . $name . ' no found');
			$this->quit();
		}
		$documentCollection = new DocumentCollection();
		$documentCollection->addDocument($document);
		$category = new Category();
		$category->setPath($path);
		$documentCollection->setCategory($category);
		return $documentCollection;
	}

	/**
	 * exportCommand($name, $paths, $useThumb = FALSE, $useFullPath = FALSE)
	 *
	 * @param string $name
	 * @param string $paths
	 * @param boolean $useThumb
	 * @param boolean $useFullPath
	 * @param integer $sortByPrefix
	 * @return void
	 */
	public function exportCommand($name, $paths, $useThumb = FALSE, $useFullPath = FALSE, $sortByPrefix = 0) {
		$name = trim($name);
		$documents = $this->documentRepository->findByCategoryPaths(explode(',', $paths));
		$documents = $documents->toArray();
		$documents = new \Doctrine\Common\Collections\ArrayCollection($documents);
		$documentExport = new DocumentExport();
		$documentExport->setUseThumb($useThumb);
		$documentExport->setUseFullPath($useFullPath);
		$documentExport->setDocuments($documents);
		$documentExport->setSortByPrefix($sortByPrefix);
		$documentExport->setName($name);
		try {
			$name = $this->documentExportService->export($documentExport);
			$this->outputLine('SUCCESS: export to ' . $name);
		} catch (\AchimFritz\Documents\Domain\FileSystem\Service\Exception $e) {
			$this->outputLine('ERROR: cannot export with ' . $e->getMessage() . ' - ' . $e->getCode());
		}
	}

	/**
	 * findnotuniq --cnt=2 --maxResults=10
	 *
	 * @param integer $cnt
	 * @param integer $maxResults
	 * @return void
	 */
	public function findNotUniqCommand($cnt = 2, $maxResults = 10) {
		$documents = $this->documentRepository->findNotUniq($cnt, $maxResults);
		foreach ($documents AS $document) {
			$this->outputLine($document->getName());
		}
	}

	/**
	 * @param string $path 
	 * @return void
	 */
	public function testDirectoryCommand($path = 'lucky/mix/Lucky3') {
		try {
			$files = $this->directoryService->getSplFileInfosInDirectory($this->getMountPoint() . PathService::PATH_DELIMITER . $path, $this->getConfiguration()->getFileExtension());
			$cntFs = count($files);
			$cntDb = 0;
			foreach ($files as $fileInfo) {
				$fileHash = sha1_file($fileInfo->getRealPath());
				$document = $this->documentRepository->findOneByFileHash($fileHash);
				$this->outputLine('INFO: testing ' . $fileInfo->getRealPath());
				if ($document instanceof Document) {
					$cntDb++;
					$this->outputLine('INFO: found ' . $document->getName());
				} 
			}
			$this->outputLine('INFO: summary ' . $path . ': ' . $cntFs . ' Files and ' . $cntDb . ' Documents');
		} catch (\AchimFritz\Documents\Domain\FileSystem\Service\Exception $e) {
			$this->outputLine('ERROR: ' . $e->getMessage() . ' - ' . $e->getCode());
		}
	}

	/**
	 * @param string $pathHead
	 * @param string $path
	 * @return void
	 */
	public function addToCategoryCommand($pathHead = 'tags', $path = 'tags/bestof') {
		$category = $this->categoryRepository->findOneByPath($path);
		if ($category instanceof Category === FALSE) {
			$this->outputLine('ERROR no category with path ' . $path);
			$this->quit();
		}
		$categories = $this->categoryRepository->findByPathHead($pathHead);
		$collection = new \Doctrine\Common\Collections\ArrayCollection();
		foreach ($categories as $c) {
			$collection->add($c);
		}

		$cnt = 0;
		$documents = $this->documentRepository->findInOneCategories($collection);
		foreach ($documents as $document) {
			if ($document->hasCategory($category) === FALSE) {
				$document->addCategory($category);
				$this->documentRepository->update($document);
				$cnt++;
			}
		}

		try {
			$this->documentPersistenceManager->persistAll();
			$this->outputLine('SUCCESS: add ' . $cnt . ' documents to ' . $path);
		} catch (\AchimFritz\Documents\Persistence\Exception $e) {
			$this->outputLine('ERROR: add to category with ' . $e->getMessage() . ' - ' . $e->getCode());
		}
	}

	/**
	 * @param string $path
	 * @param string $new
	 * @return void
	 */
	public function copyCategoryCommand($path = 'collections/2012_12_15_gisela_bilderrahmen', $new = 'collections/2012_12_15_gisela_bilderrahmen_ohne_mona') {
		$category = $this->categoryRepository->findOneByPath($path);
		$newCategory = new Category();
		$newCategory->setPath($new);
		$this->categoryRepository->add($newCategory);
		if ($category instanceof Category) {
			$documents = $this->documentRepository->findByCategory($category);
			foreach ($documents as $document) {
				$document->addCategory($newCategory);
				$cnt++;
				$this->documentRepository->update($document);
			}
		} else {
			$this->outputLine('ERROR: category not found ' . $path);
		}
		try {
			$this->documentPersistenceManager->persistAll();
			$this->outputLine('SUCCESS: add ' . $cnt . ' documents to ' . $new);
		} catch (\AchimFritz\Documents\Persistence\Exception $e) {
			$this->outputLine('ERROR: add to category with ' . $e->getMessage() . ' - ' . $e->getCode());
		}
	}

	/**
	 * @return \AchimFritz\Documents\Configuration\FileSystemDocumentConfiguration
	 */
	protected function getConfiguration() {
		return $this->configuration;
	}

	/**
	 * @return string
	 */
	protected function getMountPoint() {
		return $this->getConfiguration()->getMountPoint();
	}

	/**
	 * @return string
	 */
	protected function getFileExtension() {
		return $this->getConfiguration()->getFileExtension();
	}
		
}
