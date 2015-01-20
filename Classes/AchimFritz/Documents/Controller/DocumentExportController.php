<?php
namespace AchimFritz\Documents\Controller;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "AchimFritz.Documents".  *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use AchimFritz\Documents\Domain\Model\Facet\DocumentCollection;
use AchimFritz\Documents\Domain\Model\Category;
use TYPO3\Flow\Error\Message;
use TYPO3\Flow\Mvc\Controller\ActionRequest;
use AchimFritz\Documents\Domain\Model\Facet\FileSystemDocument\DocumentExport;

class DocumentExportController extends \AchimFritz\Rest\Controller\RestController {

	/**
	 * @var \AchimFritz\Documents\Domain\Service\FileSystem\DocumentExportService
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
	 * @param \AchimFritz\Documents\Domain\Model\Facet\FileSystemDocument\DocumentExport $documentExport
	 * @return void
	 */
	public function createAction(DocumentExport $documentExport) {
		try {
			$fileName = $this->documentExportService->export($documentExport);
			$this->view->assign('fileName', $fileName);
		} catch (\AchimFritz\Documents\Domain\Service\Exception $e) {
			$this->response->setContent('Cannot export with ' . $e->getMessage() . ' - ' . $e->getCode());
			$this->response->setStatus(500);
			throw new \TYPO3\Flow\Mvc\Exception\StopActionException();
		}
	}

}
