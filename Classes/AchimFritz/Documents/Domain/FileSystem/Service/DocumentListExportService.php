<?php
namespace AchimFritz\Documents\Domain\Service\FileSystem;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "AchimFritz\Documents". *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use AchimFritz\Documents\Domain\Service\PathService;
use AchimFritz\Documents\Domain\Model\FileSystemDocument as Document;
use AchimFritz\Documents\Domain\Model\DocumentList;
use AchimFritz\Documents\Domain\Model\DocumentListItem;

/**
 * @Flow\Scope("singleton")
 */
class DocumentListExportService extends AbstractExportService {

	/**
	 * @param DocumentList $documentList
	 * @throws Exception
	 * @return integer 
	 */
	public function export(DocumentList $documentList) {
		$name = $this->getName($documentList);
		$directory = $this->createExportDirectory($name);
		$cnt = count($documentList->getDocumentListItems());
		$preLength = strlen((string)$cnt);
		$cnt = 0;
		foreach ($documentList->getDocumentListItems() as $item) {
			$from = $this->createFromPath($item->getDocument());
			$to = $this->createToPath($item, $cnt, $directory, $preLength);
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
		$from = $document->getAbsolutePath();
		return $from;
	}

	/**
	 * @param DocumentListItem $item 
	 * @param integer $cnt 
	 * @param string $directory 
	 * @return string
	 */
	protected function createToPath(DocumentListItem $item, $cnt, $directory, $preLength = 2) {
		$pre = sprintf('%0' . $preLength . 's', ($cnt + 1));
		$name = $pre . '_' . $item->getDocument()->getFileName();
		$to = $directory . PathService::PATH_DELIMITER . $name;
		return $to;
	}


}
