<?php
namespace AchimFritz\Documents\Domain\Model;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "AchimFritz.FileSystemDocuments".*
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use AchimFritz\Documents\Domain\Service\PathService;
use AchimFritz\Documents\Domain\Model\Category;
use AchimFritz\Documents\Domain\Model\DocumentList;
use AchimFritz\Documents\Domain\Model\DocumentListItem;

/**
 * @Flow\Scope("singleton")
 */
class FileSystemDocumentListFactory {

	/**
	 * @var \AchimFritz\Documents\Domain\Repository\FileSystemDocumentRepository
	 * @Flow\Inject
	 */
	protected $documentRepository;

	/**
	 * @var \AchimFritz\Documents\Domain\Service\FileSystem\DirectoryService
	 * @Flow\Inject
	 */
	protected $directoryService;

	/**
	 * @var \AchimFritz\Documents\Configuration\FileSystemDocumentConfiguration
	 * @Flow\Inject
	 */
	protected $configuration;

	/**
	 * @var \AchimFritz\Documents\Domain\Service\PathService
	 * @Flow\Inject
	 */
	protected $pathService;

	/**
	 * @var string
	 */
	protected $extension = '';

	/**
	 * @param string $name 
	 * @return \AchimFritz\Documents\Domain\Model\DocumentList
	 * @throws Exception
	 */
	public function createFromFile($name, $path = '') {
		if (file_exists($name) === FALSE) {
			throw new Exception('no such file ' . $name, 1422200216);
		}
		$content = file_get_contents($name);
		$lines = explode("\n", $content);
		$cnt = 1;
		$documentList = $this->getDocumentList();
		if ($path !== '') {
			$category = new Category();
			$category->setPath($path);
			$documentList->setCategory($category);
		}
		foreach ($lines AS $line) {
			if ($line !== '') {
				$document = $this->getDocument($line);
				$documentListItem = new DocumentListItem();
				$documentListItem->setDocument($document);
				$documentListItem->setSorting($cnt);
				$cnt++;
				$documentList->addDocumentListItem($documentListItem);
			}
		}
		return $documentList;
	}

	/**
	 * @param string $directory 
	 * @param string $path
	 * @param boolean $byName
	 * @return \AchimFritz\Documents\Domain\Model\DocumentList
	 * @throws Exception
	 */
	public function createFromDirectory($directory, $path = '', $byName = FALSE) {
		if (is_dir($directory) === FALSE) {
			throw new Exception('no such directory ' . $directory, 1422200218);
		}
		$cnt = 1;
		$documentList = $this->getDocumentList();
		if ($path !== '') {
			$category = new Category();
			$category->setPath($path);
			$documentList->setCategory($category);
		}
		try {
			$splFileInfos = $this->directoryService->getSplFileInfosInDirectory($directory, $this->extension);
		} catch (\AchimFritz\Documents\Domain\Service\FileSystem\Exception $e) {
			throw new Exception('cannot get SplFileInfos with ' . $e->getMessage() . ' - ' . $e->getCode(), 1422200220);
		}
		foreach ($splFileInfos AS $splFileInfo) {
			if ($byName === TRUE) {
				$document = $this->getDocumentByName($splFileInfo);
			} else {
				$document = $this->getDocumentByFileHash($splFileInfo);
			}
			$item = new DocumentListItem();
			$item->setDocument($document);
			$item->setSorting($cnt);
			$cnt++;
			$documentList->addDocumentListItem($item);
		}
		return $documentList;
	}

	/**
	 * tmp for gisela_60 diashow
	 *
	 * @param \SplFileInfo $splFileInfo
	 * @return Document
	 * @throws Exception
	 */
	protected function getDocumentForGisela(\SplFileInfo $splFileInfo) {
		$fileHash = sha1_file($splFileInfo->getRealPath());
		$documents = $this->documentRepository->findByFileHash($fileHash);
		$results = array();
		$categories = array();
		foreach ($documents AS $document) {
			if ($document->getDirectoryName() === '2012_03_10_gisela_diashow') {
				$categories = $document->getCategories();
			} else {
				$results[] = $document;
			}
		}
		if (count($results) !== 1) {
			$msg = 'found not exactly one document, but ' . count($results) . ' for ' . $splFileInfo->getRealPath();
			$ok = FALSE;
			foreach ($results AS $document) {
				$msg .= ' - ' . $document->getName();
				if ($document->getName() === '2003_06_19_hochzeit_michi_silke/A10.jpg') {
					$results[0] = $document;
					$ok = TRUE;
				}
				if ($document->getName() === '2005_04_24_britta_hochzeit/dsci0048.jpg') {
					$results[0] = $document;
					$ok = TRUE;
				}
			}
			if ($ok === FALSE) {
				throw new Exception ($msg, 1422200221);
			}
		}
		$document = $results[0];
		foreach ($categories AS $category) {
			if ($document->hasCategory($category) === FALSE) {
				$document->addCategory($category);
			}
		}
		$this->documentRepository->update($document);
		return $document;
	}

	/**
	 * @param \SplFileInfo $splFileInfo
	 * @return Document
	 * @throws Exception
	 */
	protected function getDocumentByFileHash(\SplFileInfo $splFileInfo) {
		$fileHash = sha1_file($splFileInfo->getRealPath());
		$documents = $this->documentRepository->findByFileHash($fileHash);
		if (count($documents) !== 1) {
			$msg = 'found not exactly one document, but ' . count($documents) . ' for ' . $splFileInfo->getRealPath();
			foreach ($documents AS $document) {
				$msg .= ' - ' . $document->getName();
			}
			throw new Exception ($msg, 1422200221);
		}
		return $documents->current();
	}

	/**
	 * @param \SplFileInfo $splFileInfo
	 * @return Document
	 * @throws Exception
	 */
	protected function getDocumentByName(\SplFileInfo $splFileInfo) {
		$fileName = $splFileInfo->getBasename();
		$documents = $this->documentRepository->findByTail($fileName);
		if (count($documents) !== 1) {
			$msg = 'found not exactly one document, but ' . count($documents) . ' for ' . $splFileInfo->getRealPath();
			foreach ($documents AS $document) {
				$msg .= ' - ' . $document->getName();
			}
			throw new Exception ($msg, 1422200238);
		}
		return $documents->current();
	}

	/**
	 * @param string $absolutePath 
	 * @return Document
	 * @throws Exception
	 */
	protected function getDocument($absolutePath) {
		$name = $this->getNameFromAbsolutePath($absolutePath);
		$document = $this->documentRepository->findOneByName($name);
		if ($document instanceof FileSystemDocument === FALSE) {
			throw new Exception ('no such document: ' . $absolutePath, 1422200217);
		}
		return $document;
	}

	/**
	 * @param string $absolutePath 
	 * @return string
	 */
	protected function getNameFromAbsolutePath($absolutePath) {
		$absolutePath = str_replace('"', '', $absolutePath);
		$mp = $this->getConfiguration()->getMountPoint();
		$name = $this->pathService->replacePath($absolutePath, $mp . PathService::PATH_DELIMITER, '');
		return $name;
	}

	/**
	 * @return \AchimFritz\Documents\Configuration\FileSystemDocumentConfiguration
	 */
	protected function getConfiguration() {
		return $this->configuration;
	}

	/**
	 * @return \AchimFritz\Documents\Domain\Model\DocumentList
	 */
	protected function getDocumentList() {
		return new DocumentList();
	}


}
