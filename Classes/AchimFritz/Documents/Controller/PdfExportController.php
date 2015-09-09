<?php
namespace AchimFritz\Documents\Controller;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "AchimFritz.Documents".  *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Error\Message;
use TYPO3\Flow\Mvc\Controller\ActionRequest;
use AchimFritz\Documents\Domain\FileSystem\Facet\ImageDocument\PdfExport;

class PdfExportController extends RestController {

	/**
	 * @var \AchimFritz\Documents\Domain\FileSystem\Service\ImageDocument\PdfExportService
	 * @Flow\Inject
	 */
	protected $pdfExportService;

	/**
	 * @var string
	 */
	protected $resourceArgumentName = 'pdfExport';

	/**
	 * Supported content types. Needed for HTTP content negotiation.
	 * @var array
	 */
	protected $supportedMediaTypes = array('application/pdf');

	/**
	 * @var array
	 */
	protected $viewFormatToObjectNameMap = array('pdf' => 'AchimFritz\\Documents\\Mvc\\View\\FileTransferView');


	/**
	 * @return void
	 */
	public function initializeCreateAction() {
		parent::initializeCreateAction();
		$propertyMappingConfiguration = $this->arguments[$this->resourceArgumentName]->getPropertyMappingConfiguration();

		// documents
		$propertyMappingConfiguration->forProperty('documents');
		$sub = $propertyMappingConfiguration->getConfigurationFor('documents');
		$sub->allowAllProperties();
	}

	/**
	 * @param \AchimFritz\Documents\Domain\FileSystem\Facet\ImageDocument\PdfExport $pdfExport
	 * @return void
	 */
	public function createAction(PdfExport $pdfExport) {
		try {
			$fileName = $this->pdfExportService->createPdf($pdfExport);
			$this->view->assign('fileName', $fileName);
		} catch (\AchimFritz\Documents\Domain\Service\Exception $e) {
			$this->response->setHeader('Content-Type', 'text/html' . '; charset=UTF-8', TRUE);
			$this->response->setStatus(500, 'Cannot export with ' . $e->getMessage() . ' - ' . $e->getCode());
			throw new \TYPO3\Flow\Mvc\Exception\StopActionException();
		}
	}

}
