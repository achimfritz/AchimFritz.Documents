<?php
namespace AchimFritz\Documents\Command;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "De.AchimFritz.Intranet".*
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use AchimFritz\Documents\Domain\Model\Facet\FileSystemDocument\ImageDocument\PdfExport;

/**
 * @Flow\Scope("singleton")
 */
class ImageDocumentCommandController extends AbstractFileSystemDocumentCommandController {

	/**
	 * @var \AchimFritz\Documents\Domain\Service\FileSystem\ImageDocument\PdfExportService
	 * @Flow\Inject
	 */
	protected $pdfExportService;

	/**
	 * @var \AchimFritz\Documents\Domain\Repository\ImageDocumentRepository
	 * @Flow\Inject
	 */
	protected $documentRepository;
		
	/**
	 * @var \AchimFritz\Documents\Domain\Model\ImageDocumentFactory
	 * @Flow\Inject
	 */
	protected $documentFactory;

	/**
	 * @var \AchimFritz\Documents\Domain\Model\Facet\FileSystemDocument\ImageDocument\IntegrityFactory
	 * @Flow\Inject
	 */
	protected $integrityFactory;

	/**
	 * @var \AchimFritz\Documents\Domain\Service\ImageIndexService
	 * @Flow\Inject
	 */
	protected $indexService;


	/**
	 * @return string
	 */
	protected function getMountPoint() {
		return $this->settings['imageDocument']['mountPoint'];
	}

	/**
	 * @return string
	 */
	protected function getExtension() {
		return 'jpg';
	}

	/**
	 * @param string $paths 
	 * @param integer $columns 
	 * @param integer $size 
	 * @param integer $dpi 
	 * @param integer $orientation 
	 * @return void
	 */
	public function createPdfCommand($paths = 'categories/lucky/klamotten', $columns=6, $size=PdfExport::SIZE_A4, $dpi=300, $orientation=PdfExport::ORIENTATION_PORTRAIT) {
		$documents = $this->documentRepository->findByCategoryPaths(explode(',', $paths));
		$documents = $documents->toArray();
		$documents = new \Doctrine\Common\Collections\ArrayCollection($documents);
		$pdfExport = new PdfExport();
		$pdfExport->setDocuments($documents);
		$pdfExport->setSize($size);
		$pdfExport->setDpi($dpi);
		$pdfExport->setColumns($columns);
		$pdfExport->setOrientation($orientation);
		try {
			$fileName = $this->pdfExportService->createPdf($pdfExport);
			$this->outputLine('SUCCESS: ' . $fileName);
		} catch (\AchimFritz\Documents\Domain\Service\FileSystem\Exception $e) {
			$this->outputLine('ERROR: cannot export with ' . $e->getMessage() . ' - ' . $e->getCode());
		}
	}
}
