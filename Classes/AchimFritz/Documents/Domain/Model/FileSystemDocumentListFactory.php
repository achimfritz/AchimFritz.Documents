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
	 * @return \AchimFritz\Documents\Domain\Model\DocumentList
	 * @throws Exception
	 */
	public function createFromDirectory($directory, $path = '') {
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
			$document = $this->getDocumentByFileHash($splFileInfo);
			$item = new DocumentListItem();
			$item->setDocument($document);
			$item->setSorting($cnt);
			$cnt++;
			$documentList->addDocumentListItem($item);
		}
		return $documentList;
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
?>