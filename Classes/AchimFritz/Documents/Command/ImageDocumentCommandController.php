<?php
namespace AchimFritz\Documents\Command;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "De.AchimFritz.Intranet".*
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use AchimFritz\Documents\Domain\Model\Facet\FileSystemDocument\DocumentExport;

/**
 * @Flow\Scope("singleton")
 */
class ImageDocumentCommandController extends AbstractFileSystemDocumentCommandController {

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
	 * @var \AchimFritz\Documents\Domain\Service\FileSystem\ImageDocument\DocumentExportService
	 * @Flow\Inject
	 */
	protected $documentExportService;


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
	 * @return void
	 */
	public function createPdfCommand($paths = 'categories/lucky/klamotten') {
		$documents = $this->documentRepository->findByCategoryPaths(explode(',', $paths));
		$documents = $documents->toArray();
		$documents = new \Doctrine\Common\Collections\ArrayCollection($documents);
		$documentExport = new DocumentExport();
		$documentExport->setDocuments($documents);
		$documentExport->setUseThumb(TRUE);
		try {
			$cnt = $this->documentExportService->createPdf($documentExport);
			$this->outputLine('SUCCESS: ' . $cnt . ' documents');
		} catch (\AchimFritz\Documents\Domain\Service\FileSystem\Exception $e) {
			$this->outputLine('ERROR: cannot export with ' . $e->getMessage() . ' - ' . $e->getCode());
		}
	}
}
