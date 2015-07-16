<?php
namespace AchimFritz\Documents\Controller;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "AchimFritz.Documents".  *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use AchimFritz\Documents\Domain\Model\DocumentList;
use TYPO3\Flow\Error\Message;
use TYPO3\Flow\Mvc\Controller\ActionRequest;
use TYPO3\Flow\Property\TypeConverter\PersistentObjectConverter;

class DocumentListRemoveController extends RestController {

	/**
	 * @Flow\Inject
	 * @var \AchimFritz\Documents\Domain\Service\DocumentListService
	 */
	protected $documentListService;

	/**   
	 * @var \AchimFritz\Documents\Persistence\DocumentsPersistenceManager
	 * @Flow\Inject
	 */
	protected $documentPersistenceManager;

	/**
	 * @var string
	 */
	protected $resourceArgumentName = 'documentList';

	/**
	 * @return void
	 */
	public function initializeCreateAction() {
		parent::initializeCreateAction();
		$propertyMappingConfiguration = $this->arguments[$this->resourceArgumentName]->getPropertyMappingConfiguration();

		// category
		$propertyMappingConfiguration->forProperty('category');
		$sub = $propertyMappingConfiguration->getConfigurationFor('category');
		$sub->setTypeConverterOption('TYPO3\Flow\Property\TypeConverter\PersistentObjectConverter', PersistentObjectConverter::CONFIGURATION_CREATION_ALLOWED, TRUE);
		$sub->allowAllProperties();

		// documentListItems
		$propertyMappingConfiguration->forProperty('documentListItems');
		$sub = $propertyMappingConfiguration->getConfigurationFor('documentListItems');
		$sub->allowAllProperties();

		for ($i = 0; $i < 20; $i++) {
			$sub->forProperty($i)->allowAllProperties();
			$sub->forProperty($i)->setTypeConverterOption('TYPO3\Flow\Property\TypeConverter\PersistentObjectConverter', PersistentObjectConverter::CONFIGURATION_CREATION_ALLOWED, TRUE);
		}

	}

	/**
	 * @param \AchimFritz\Documents\Domain\Model\DocumentList $documentList
	 * @return void
	 */
	public function createAction(DocumentList $documentList) {
		try {
			$documentList = $this->documentListService->remove($documentList);
			try {
				$this->documentPersistenceManager->persistAll();
				$this->addFlashMessage('Documents removed from ' . $documentList->getCategory()->getPath());
			} catch (\AchimFritz\Documents\Persistence\Exception $e) {
				$this->handleException($e);
			}
			if ($this->request->getReferringRequest() instanceof ActionRequest) {
				$this->redirectToRequest($this->request->getReferringRequest());
			} else {
				$this->redirect('list', 'DocumentList', NULL, array('documentList' => $documentList));
			}
		} catch (AchimFritz\Documents\Domain\Service\Exception $e) {
			$this->handleException($e);
			if ($this->request->getReferringRequest() instanceof ActionRequest) {
				$this->redirectToRequest($this->request->getReferringRequest());
			} else {
				$this->redirect('list', 'DocumentList');
			}
		}
	}

}
