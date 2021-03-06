<?php
namespace AchimFritz\Documents\Controller;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "AchimFritz.Documents".  *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Mvc\Controller\ActionRequest;
use AchimFritz\Documents\Domain\FileSystem\Facet\DocumentExport;

class DocumentExportController extends RestController {

	/**
	 * @var \AchimFritz\Documents\Domain\FileSystem\Service\DocumentExportService
	 * @Flow\Inject
	 */
	protected $documentExportService;

	/**
	 * @var string
	 */
	protected $resourceArgumentName = 'documentExport';

	/**
	 * Supported content types. Needed for HTTP content negotiation.
	 * @var array
	 */
	protected $supportedMediaTypes = array('application/zip');

	/**
	 * @var array
	 */
	protected $viewFormatToObjectNameMap = array('zip' => 'AchimFritz\\Documents\\Mvc\\View\\FileTransferView');


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
	 * @param \AchimFritz\Documents\Domain\FileSystem\Facet\DocumentExport $documentExport
	 * @return void
	 */
	public function createAction(DocumentExport $documentExport) {
		try {
			$fileName = $this->documentExportService->export($documentExport);
			$this->view->assign('fileName', $fileName);
		} catch (\AchimFritz\Documents\Exception $e) {
			$this->response->setHeader('Content-Type', 'text/html' . '; charset=UTF-8', TRUE);
			$this->response->setStatus(500, 'Cannot export with ' . $e->getMessage() . ' - ' . $e->getCode());
			throw new \TYPO3\Flow\Mvc\Exception\StopActionException();
		}
	}

}
