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
	 * @var \AchimFritz\Documents\Configuration\ImageDocumentConfiguration
	 * @Flow\Inject
	 */
	protected $imageDocumentConfiguration;

	/**
	 * @return \AchimFritz\Documents\Configuration\ImageDocumentConfiguration
	 */
	protected function getConfiguration() {
		return $this->imageDocumentConfiguration;
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

	/**
	 * integrityDetail --directory=2015_05_05_venedig
	 *
	 * @param string $directory
	 * @return void
	 */
	public function integrityDetailCommand($directory) {
		try {
			$integrity = $this->integrityFactory->createIntegrity($directory);
			$this->outputLine('');
			$this->outputLine('Count Solr: ' . $integrity->getCountSolr());
			$this->outputLine('Count FS: ' . $integrity->getCountFileSystem());
			$this->outputLine('Count DB: ' . count($integrity->getPersistedDocuments()));
			$this->outputLine('Count Thumbs: ' . count($integrity->getThumbs()));
			$this->outputLine('');
			$this->outputLine('timestamps initialized : ' . (int)$integrity->getTimestampsAreInitialized());
			$this->outputLine('images rotated: ' . (int)$integrity->getImageIsRotated());
			$this->outputLine('isExif: ' . (int)$integrity->getIsExif());
			$this->outputLine('geeqie metadata exists: ' . (int)$integrity->getGeeqieMetadataExists());
			$this->outputLine('');
			$this->outputLine('ready for rotation: ' . (int)$integrity->getReadyForRotation());
			$this->outputLine('ready for thumbs: ' . (int)$integrity->getReadyForThumbs());
			$this->outputLine('');
			$nextStep = $integrity->getNextStep();
			if ($nextStep === '') {
				if ($integrity->getImageIsRotated() === FALSE) {
					$this->outputLine('no next step because no image is rotated');
				} else {
					$this->outputLine('no next step, surf finished');
				}
				
			} else {
				$this->outputLine('next Step');
				$this->outputLine('./flow achimfritz.documents:imagesurf:' . $nextStep . ' --name=' . $directory);
			}
		} catch (\AchimFritz\Documents\Domain\Model\Facet\FileSystemDocument\Exception $e) {
			$this->outputLine('ERROR: ' . $e->getMessage() . ' - ' . $e->getCode());
		}
	}

}
