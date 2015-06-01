<?php
namespace AchimFritz\Documents\Domain\Service\FileSystem\Mp3Document;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "AchimFritz\Documents". *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use AchimFritz\Documents\Domain\Model\Facet\DocumentCollection;

/**
 * @Flow\Scope("singleton")
 */
class Id3TagWriterService {

	/**
	 * @var \AchimFritz\Documents\Linux\Command
	 * @Flow\Inject
	 */
	protected $linuxCommand;

	/**
	 * @param PdfExport $documentExport
	 * @throws Exception
	 * @return string
	 */
	public function writeFromDocumentCollection(DocumentCollection $documentCollection) {
		/*
		$cmd = $this->getCommand($documentExport);
		try {
			$this->linuxCommand->executeCommand($cmd);
		} catch (\AchimFritz\Documents\Linux\Exception $e) {
			throw new Exception('cannot execute command ' . $cmd, 1420993448);
		}
		return $this->getOutName();
		*/
	}

}
