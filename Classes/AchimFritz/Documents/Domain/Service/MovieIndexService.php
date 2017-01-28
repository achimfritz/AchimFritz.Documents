<?php
namespace AchimFritz\Documents\Domain\Service;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "AchimFritz.Documents".  *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use AchimFritz\Documents\Domain\Model\FileSystemDocument;

/**
 * @Flow\Scope("singleton")
 */
class MovieIndexService extends FileSystemDocumentIndexService {

	/**
	 * @var \AchimFritz\Documents\Domain\Repository\MovieDocumentRepository
	 * @Flow\Inject
	 */
	protected $documentRepository;

	/**
	 * @var \AchimFritz\Documents\Domain\Factory\MovieDocumentFactory
	 * @Flow\Inject
	 */
	protected $documentFactory;

	/**
	 * @param FileSystemDocument $document
	 * @param FileSystemDocument $createdDocument
	 * @return FileSystemDocument
	 */
	protected function updateDocumentWithNewCreated(FileSystemDocument $document, FileSystemDocument $createdDocument) {
		$document = parent::updateDocumentWithNewCreated($document, $createdDocument);
		$document->setFfmpeg($createdDocument->getFfmpeg());
		return $document;
	}

}
