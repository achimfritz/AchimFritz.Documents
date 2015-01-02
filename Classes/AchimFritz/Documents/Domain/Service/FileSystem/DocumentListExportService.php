<?php
namespace AchimFritz\Documents\Domain\Service\FileSystem;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "AchimFritz\Documents". *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use AchimFritz\Documents\Domain\Service\PathService;
use AchimFritz\Documents\Domain\Model\ImageDocument;
use AchimFritz\Documents\Domain\Model\Mp3Document;
use AchimFritz\Documents\Domain\Model\FileSystemDocument as Document;
use AchimFritz\Documents\Domain\Model\DocumentList;
use AchimFritz\Documents\Domain\Model\DocumentListItem;

/**
 * @Flow\Scope("singleton")
 */
class DocumentListExportService extends AbstractExportService {

	/**
	 * @var \AchimFritz\Documents\Domain\Model\Facet\FileSystemDocument\ImageDocument\FileSystemFactory
	 * @Flow\Inject
	 */
	protected $imageFileSystemFactory;

	/**
	 * @var \AchimFritz\Documents\Domain\Model\Facet\FileSystemDocument\Mp3Document\FileSystemFactory
	 * @Flow\Inject
	 */
	protected $mp3FileSystemFactory;

	/**
	 * @param DocumentList $documentList
	 * @throws Exception
	 * @return integer 
	 */
	public function export(DocumentList $documentList) {
		$name = $this->getName($documentList);
		$directory = $this->createExportDirectory($name);
		$cnt = 0;
		foreach ($documentList->getDocumentListItems() as $item) {
			$from = $this->createFromPath($item->getDocument());
			$to = $this->createToPath($item, $cnt, $directory);
			$this->copyFile($from, $to);
			$cnt++;
		}
		return $cnt;
	}

	/**
	 * @param DocumentList $documentList 
	 * @return string
	 */
	protected function getName(DocumentList $documentList) {
		$category = $documentList->getCategory();
		$name = implode('_', explode(PathService::PATH_DELIMITER, $category->getPath()));
		return $name;
	}

	/**
	 * @param Document $document
	 * @return string
	 */
	protected function createFromPath(Document $document) {
		if ($document instanceof ImageDocument) {
			$fileSystem = $this->imageFileSystemFactory->create($document);
		} elseif ($document instanceof Mp3Document) {
			$fileSystem = $this->mp3FileSystemFactory->create($document);
		} else {
			throw new Exception('need image or mp3 document', 1420125926);
		}
		$from = $fileSystem->getAbsolutePath();
		return $from;
	}

	/**
	 * @param DocumentListItem $item 
	 * @param integer $cnt 
	 * @param string $directory 
	 * @return string
	 */
	protected function createToPath(DocumentListItem $item, $cnt, $directory) {
		$pre = sprintf('%02s', ($cnt + 1));
		$name = $pre . '_' . $item->getDocument()->getFileName();
		$to = $directory . PathService::PATH_DELIMITER . $name;
		return $to;
	}


}
