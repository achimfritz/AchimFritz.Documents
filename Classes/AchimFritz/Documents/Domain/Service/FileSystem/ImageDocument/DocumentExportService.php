<?php
namespace AchimFritz\Documents\Domain\Service\FileSystem\ImageDocument;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "AchimFritz\Documents". *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use AchimFritz\Documents\Domain\Service\FileSystem\AbstractDocumentExportService;
use AchimFritz\Documents\Domain\Model\Facet\FileSystemDocument\DocumentExport;

/**
 * @Flow\Scope("singleton")
 */
class DocumentExportService extends AbstractDocumentExportService {

	/**
	 * @var \AchimFritz\Documents\Linux\Command
	 * @Flow\Inject
	 */
	protected $linuxCommand;

	/**
	 * @param DocumentExport $documentExport
	 * @return string
	 */
	public function getCreatePdfCommand(DocumentExport $documentExport) {
		$cmd = 'montage ';
		foreach ($documentExport->getDocuments() as $document) {
			$from = $this->createFromPath($document, $documentExport);
			$cmd .= $from . ' ';
		}
		// wants per page
		// -> 612x792 612x792+0+0
		// -> tile cols x rows
		$cmd .= ' -tile 3x5 -geometry 160x120+10+10 /tmp/out.pdf';
		return $cmd;
	}

	/**
	 * @param DocumentExport $documentExport
	 * @throws Exception
	 * @return integer
	 */
	public function createPdf(DocumentExport $documentExport) {
		$cmd = $this->getCreatePdfCommand($documentExport);
		try {
			$this->linuxCommand->executeCommand($cmd);
		} catch (\AchimFritz\Documents\Linux\Exception $e) {
			throw new Exception('cannot execute command ' . $cmd, 1420993448);
		}
		return count($documentExport->getDocuments());
	}


}
