<?php
namespace AchimFritz\Documents\Domain\Service\FileSystem;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "AchimFritz\Documents". *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use AchimFritz\Documents\Domain\Service\PathService;
use AchimFritz\Documents\Domain\Model\FileSystemDocument as Document;
use AchimFritz\Documents\Domain\Model\ImageDocument;
use AchimFritz\Documents\Domain\Model\Facet\FileSystemDocument\DocumentExport;

/**
 * @Flow\Scope("singleton")
 */
abstract class AbstractDocumentExportService extends AbstractExportService {

	/**
	 * @param DocumentExport $documentExport
	 * @throws Exception
	 * @return integer
	 */
	public function export(DocumentExport $documentExport) {
		$directory = $this->createExportDirectory($documentExport->getName());
		$cnt = 0;
		foreach ($documentExport->getDocuments() as $document) {
			$from = $this->createFromPath($document, $documentExport);
			$to = $this->createToPath($document, $documentExport, $cnt, $directory);
			$this->copyFile($from, $to);
			$cnt++;
		}
		return $cnt;
	}

	/**
	 * @param Document $document
	 * @param DocumentExport $documentExport
	 * @return string
	 */
	protected function createFromPath(Document $document, DocumentExport $documentExport) {
		$from = $document->getAbsolutePath();
		if ($documentExport->getUseThumb() === TRUE) {
			if ($document instanceof ImageDocument === FALSE) {
				throw new Exception('thumbs only supported for imageDocuments', 1416762881);
			}
			$from = $document->getAbsoluteWebThumbPath();
		}
		return $from;
	}

	/**
	 * @param Document $document
	 * @param DocumentExport $documentExport 
	 * @param integer $cnt
	 * @param string $directory
	 * @return string
	 * @throws Exception
	 */
	protected function createToPath(Document $document, DocumentExport $documentExport, $cnt, $directory) {
		if ($documentExport->getUseFullPath() === TRUE) {
			$directory = $this->createFullPathDirectory($directory, $document);
		}
		$name = $document->getFileName();
		$prefix = '';
		switch ($documentExport->getSortByPrefix()) {
			case DocumentExport::SORT_BY_PREFIX_TIMESTAMP:
				$prefix = $document->getMDateTime()->format('u') . '_';
				break;
			case DocumentExport::SORT_BY_PREFIX_COUNTER:
				$prefix = sprintf('%0' . $documentExport->getCounterLength() . 's', ($cnt + 1)). '_';
				break;
			default:
				break;
		}
		$to = $directory . PathService::PATH_DELIMITER . $prefix . $name;
		return $to;
	}

}
